<?php


namespace addons\ldcms\utils;


use PDO;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use think\Config;
use think\Db;
use think\Exception;

class ThemeService
{
    protected static $infoRange='ldcmsthemeinfo';

    public static function getThemesViewDir(){
        return ADDON_PATH.'ldcms'.DS.'view'.DS;
    }

    /**
     * 获取备份目录
     */
    public static function getThemesBackupDir()
    {
        $dir = RUNTIME_PATH . 'ldcms' . DS.'view'.DS;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * 获取源资源文件夹
     * @param string $name 插件名称
     * @return  string
     */
    public static function getSourceAssetsDir($name)
    {
        return ADDON_PATH . str_replace("/", DS, "ldcms/view/{$name}/assets");
    }

    /**
     * 获取目标资源文件夹
     * @param string $name 插件名称
     * @return  string
     */
    public static function getDestAssetsDir($name)
    {
        return ROOT_PATH . str_replace("/", DS, "public/assets/addons/ldcms/{$name}");
    }

    /**
     * 获取 uploads文件夹
     * @param $name
     * @return string
     */
    public static function getSourceUploadsDir($name)
    {
        return ADDON_PATH . str_replace("/", DS, "ldcms/view/{$name}/uploads");
    }

    /**
     * 获取目标资源文件夹
     * @param $name
     * @return string
     */
    public static function getDestUploadsDir($name)
    {
        return ROOT_PATH . str_replace("/", DS, "public/uploads");
    }

    /**
     * 获取指定模板的目录
     */
    public static function getThemesDir($name)
    {
        return self::getThemesViewDir() . $name . DS;
    }

    public static function getThemeList()
    {
        $themesViewDir=ADDON_PATH.DS.'ldcms'.DS.'view'.DS;
        $results = scandir($themesViewDir);
//        $list = [
//            'default'=>[
//                'name'=>'default',
//                'title'=>'默认中文模板',
//                'author'=>'ldcms',
//                'install'=>1,
//                'thumb'=>'default.jpg',
//                'is_testdata'=>1
//            ],
//            'en'=>[
//                'name'=>'en',
//                'title'=>'默认英文模板',
//                'author'=>'ldcms',
//                'install'=>1,
//                'thumb'=>'en.png',
//                'is_testdata'=>1
//            ]
//        ];
        $list=[];
        foreach ($results as $name) {
            if ($name === '.' or $name === '..') {
                continue;
            }

            if (is_file($themesViewDir . $name)) {
                continue;
            }

            $themeDir = $themesViewDir . $name . DS;
            if (!is_dir($themeDir)) {
                continue;
            }

            $info_file = $themeDir . 'info.ini';
            if (!is_file($info_file)) {
                $info=[
                    'name'=>$name,
                    'title'=>$name.'模板',
                    'author'=>'lande',
                    'install'=>1,
                    'thumb'=>$name.'.png',
                ];
                self::writeInfo($themeDir,$info);
            }

            $info = Config::parse($info_file, '', "ldcms-theme-info-{$name}");
            if (!isset($info['name'])||$name!==$info['name']) {
                continue;
            }

            $testdata_file=$themeDir.'testdata.sql';
            if (is_file($testdata_file)) {
                $info['is_testdata'] = 1;
            }else{
                $info['is_testdata'] = 0;
            }

            $list[$name] = $info;
        }
        return $list;
    }

    /*获取已安装的模板*/
    public static function getInstallThemeList()
    {
        $themes=self::getThemeList();
        foreach ($themes as $k=>$v){
            if($v['install']==0){
                unset($themes[$k]);
            }
        }
        return $themes;
    }

    /**
     * 上传安装
     * @param $file
     * @param false $force
     * @param array $extend
     * @return array|mixed
     * @throws Exception
     */
    public static function local($file,$extend = [])
    {
        $themesTempDir = self::getThemesBackupDir();
        if (!$file || !$file instanceof \think\File) {
            throw new Exception('No file upload or server upload limit exceeded');
        }
        $uploadFile = $file->rule('uniqid')->validate(['size' => 102400000, 'ext' => 'zip'])->move($themesTempDir);
        if (!$uploadFile) {
            // 上传失败获取错误信息
            throw new Exception(__($file->getError()));
        }
        $fileinfo=$file->getInfo();
        $tmpFile = $themesTempDir . $uploadFile->getSaveName();

        $info = [];
        $zip = new ZipFile();
        try {
            // 打开插件压缩包
            try {
                $zip->openFile($tmpFile);
            } catch (ZipException $e) {
                @unlink($tmpFile);
                throw new Exception('Unable to open the zip file');
            }
            $config = self::getInfoIni($zip);

            // 判断模板标识
            $name = isset($config['name']) ? $config['name'] : '';
            if (!$name) {
                throw new Exception('Theme info file data incorrect');
            }

            // 判断模板是否存在
            if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
                throw new Exception('Theme name incorrect');
            }

            // 判断新模板是否存在
            $newThemeDir = self::getThemesDir($name);
            if (is_dir($newThemeDir)) {
                throw new Exception('Theme already exists');
            }

            //创建模板目录
            @mkdir($newThemeDir, 0755, true);

            // 解压到模板目录
            try {
                $zip->extractTo($newThemeDir);
            } catch (ZipException $e) {
                @unlink($newThemeDir);
                throw new Exception('Unable to extract the file');
            }

            try {
                $info = self::getInfo($name);
                $info['install'] = 1;
                self::setInfo($name, $info);
                if(is_file($newThemeDir.$info['thumb'])){
                    copy($newThemeDir.$info['thumb'], '.'.DS."assets".DS."addons".DS."ldcms".DS."admin".DS."img".DS.$info['thumb']);
                }
            } catch (\Exception $e) {
                @rmdirs($newThemeDir);
                throw new Exception(__($e->getMessage()));
            }

        } catch (Exception $e) {
            throw new Exception(__($e->getMessage()));
        } finally {
            $zip->close();
            unset($uploadFile);
            @unlink($tmpFile);
        }

        return $info;
    }

    public static function install($name)
    {
        try {
            $newThemeDir = self::getThemesDir($name);
            $info = self::getInfo($name);
            $info['install'] = 1;
            self::setInfo($name, $info);
            if(is_file($newThemeDir.$info['thumb'])){
                copy($newThemeDir.$info['thumb'], '.'.DS."assets".DS."addons".DS."ldcms".DS."admin".DS."img".DS.$info['thumb']);
            }

        }catch (Exception $e) {
            throw new Exception(__($e->getMessage()));
        }
    }

    /**
     * 匹配配置文件中info信息
     * @param ZipFile $zip
     * @return array|false
     * @throws Exception
     */
    protected static function getInfoIni($zip)
    {
        $config = [];
        // 读取插件信息
        try {
            $info = $zip->getEntryContents('info.ini');
            $config = parse_ini_string($info);
        } catch (ZipException $e) {
            throw new Exception('Unable to extract the file');
        }
        return $config;
    }

    public static function setInfo($name = '', $value = [])
    {
        $info = self::getInfo($name);
        $array = array_merge($info, $value);
        Config::set($name, $array, self::$infoRange);
        $file = self::getThemesViewDir() . $name . DS . 'info.ini';

        $res = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";
                foreach ($val as $skey => $sval) {
                    $res[] = "$skey = " . (is_numeric($sval) ? $sval : $sval);
                }
            }
            else {
                $res[] = "$key = " . (is_numeric($val) ? $val : $val);
            }
        }
        if (file_put_contents($file, implode("\n", $res) . "\n", LOCK_EX)) {
            //清空当前配置缓存
            Config::set($name, null, self::$infoRange);
        }
        else {
            throw new Exception("文件没有写入权限");
        }
        return true;
    }

    public static function getInfo($name = '', $force = false)
    {
        if (!$force) {
            $info = Config::get($name, self::$infoRange);
            if ($info) {
                return $info;
            }
        }
        $info = [];
        $infoFile = self::getThemesViewDir().$name.DS . 'info.ini';
        if (is_file($infoFile)) {
            $info = Config::parse($infoFile, '', "ldcms-theme-info-{$name}", self::$infoRange);
        }
        Config::set($name, $info, self::$infoRange);

        return $info ? $info : [];
    }

    public static function importsql($name, $fileName = null)
    {
        $fileName = is_null($fileName) ? 'testdata.sql' : $fileName;
        $sqlFile = self::getThemesDir($name) . $fileName;
        if (is_file($sqlFile)) {
            $tables=self::renderTable();

            foreach ($tables as $table){
                Db::table($table)->delete(true);
            }

            $lines = file($sqlFile);
            $templine = '';
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 2) == '/*') {
                    continue;
                }

                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    $templine = str_ireplace('__PREFIX__', config('database.prefix'), $templine);
                    $templine = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $templine);
                    try {
                        Db::getPdo()->exec($templine);
                    } catch (\PDOException $e) {
                        //$e->getMessage();
                    }
                    $templine = '';
                }
            }
        }
        return true;
    }

    protected static function renderTable()
    {
        $tableList = [];
        $dbname = \think\Config::get('database.database');
        $list = \think\Db::query("SELECT `TABLE_NAME`,`TABLE_COMMENT` FROM `information_schema`.`TABLES` where `TABLE_SCHEMA` = '{$dbname}';");
        foreach ($list as $key => $row) {
            if(strpos($row['TABLE_NAME'],'_ldcms_')!==false){
                $tableList[] = $row['TABLE_NAME'];
            }
        }
        return $tableList;
    }

    /*备份 ldcms 表字段和数据*/
    public function backupldcms($lang)
    {
        $database = config('database');
        $db=new PDO('mysql:host=' . $database['hostname'] . ';dbname=' . $database['database'] . '; port=' . $database['hostport'], $database['username'], $database['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        # COUNT
        $ct = 0;
        # CONTENT
        $sqldump = '';
        # COPYRIGHT & OPTIONS
        $sqldump .= "-- SQL Dump by Erik Edgren\n";
        $sqldump .= "-- version 1.0\n";
        $sqldump .= "--\n";
        $sqldump .= "-- SQL Dump created: " . date('F jS, Y \@ g:i a') . "\n\n";
        $sqldump .= "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";";
        $sqldump .= "\n\n\n\n-- --------------------------------------------------------\n\n\n\n";
//        $tables = $db->query("SHOW FULL TABLES WHERE Table_Type != 'VIEW'");
        $tables=self::renderTable();
        # LOOP: Get the tables
        foreach ($tables AS $table) {
            // 忽略表
//            if (in_array($table, $this->ignoreTables)) {
//                continue;
//            }
            # COUNT
            $ct++;
            /** ** ** ** ** **/
            # DATABASE: Count the rows in each tables
            $count_rows = $db->prepare("SELECT * FROM `" . $table . "`");
            $count_rows->execute();
            $c_rows = $count_rows->columnCount();
            # DATABASE: Count the columns in each tables
            $count_columns = $db->prepare("SELECT COUNT(*) FROM `" . $table . "`");
            $count_columns->execute();
            $c_columns = $count_columns->fetchColumn();
            $__table__=str_ireplace(config('database.prefix'),'__PREFIX__',  $table);
            /** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** **/
            # MYSQL DUMP: Remove tables if they exists
            $sqldump .= "--\n";
            $sqldump .= "-- Remove the table if it exists\n";
            $sqldump .= "--\n\n";
            $sqldump .= "DROP TABLE IF EXISTS `" . $__table__ . "`;\n\n\n";
            /** ** ** ** ** **/
            # MYSQL DUMP: Create table if they do not exists
            $sqldump .= "--\n";
            $sqldump .= "-- Create the table if it not exists\n";
            $sqldump .= "--\n\n";
            # LOOP: Get the fields for the table
            foreach ($db->query("SHOW CREATE TABLE `" . $table . "`") AS $field) {

                $field['Create Table']=str_ireplace(config('database.prefix'),'__PREFIX__',  $field['Create Table']);
                $sqldump .= str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $field['Create Table']);
            }
            # MYSQL DUMP: New rows
            $sqldump .= ";\n\n\n";
            /** ** ** ** ** **/
            # CHECK: There are one or more columns
            if ($c_columns != 0) {
                # MYSQL DUMP: List the data for each table
                $sqldump .= "--\n";
                $sqldump .= "-- List the data for the table\n";
                $sqldump .= "--\n\n";

                # MYSQL DUMP: Insert into each table
                $sqldump .= "INSERT INTO `" . $__table__ . "` (";
                # MYSQL DUMP: Insert into each table
//                $sqldump .= "INSERT INTO `" . $table . "` (";
                # ARRAY
                $rows = [];
                $numeric = [];
                # LOOP: Get the tables
                foreach ($db->query("DESCRIBE `" . $table . "`") AS $row) {
                    $rows[] = "`" . $row[0] . "`";
                    $numeric[] = (bool)preg_match('#^[^(]*(BYTE|COUNTER|SERIAL|INT|LONG$|CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER)#i', $row[1]);
                }
                $sqldump .= implode(', ', $rows);
                $sqldump .= ") VALUES\n";
                # COUNT
                $c = 0;
                # LOOP: Get the tables
                foreach ($db->query("SELECT * FROM `" . $table . "`") AS $data) {
                    # COUNT
                    $c++;
                    /** ** ** ** ** **/
                    $sqldump .= "(";
                    # ARRAY
                    $cdata = [];
                    # LOOP
                    for ($i = 0; $i < $c_rows; $i++) {
                        $value = $data[$i];

                        if (is_null($value)) {
                            $cdata[] = "NULL";
                        } elseif ($numeric[$i]) {
                            $cdata[] = $value;
                        } else {
                            $cdata[] = $db->quote($value);
                        }
                    }
                    $sqldump .= implode(', ', $cdata);
                    $sqldump .= ")";
                    $sqldump .= ($c % 600 != 0 ? ($c_columns != $c ? ',' : ';') : '');
                    # CHECK
                    if ($c % 600 == 0) {
                        $sqldump .= ";\n\n";
                    } else {
                        $sqldump .= "\n";
                    }
                    # CHECK
                    if ($c % 600 == 0) {
                        $sqldump .= "INSERT INTO `" . $__table__ . "`(";
                        # ARRAY
                        $rows = [];
                        # LOOP: Get the tables
                        foreach ($db->query("DESCRIBE `" . $table . "`") AS $row) {
                            $rows[] = "`" . $row[0] . "`";
                        }
                        $sqldump .= implode(', ', $rows);
                        $sqldump .= ") VALUES\n";
                    }
                }
            }
        }

        $sqldump .= "\n\n\n";
        // Backup views
        $tables = $db->query("SHOW FULL TABLES WHERE Table_Type = 'VIEW'");
        # LOOP: Get the tables
        foreach ($tables AS $table) {
//            // 忽略表
//            if (in_array($table, $this->ignoreTables)) {
//                continue;
//            }
            foreach ($db->query("SHOW CREATE VIEW `" . $table . "`") AS $field) {
                $sqldump .= "--\n";
                $sqldump .= "-- Remove the view if it exists\n";
                $sqldump .= "--\n\n";
                $sqldump .= "DROP VIEW IF EXISTS `{$field[0]}`;\n\n";
                $sqldump .= "--\n";
                $sqldump .= "-- Create the view if it not exists\n";
                $sqldump .= "--\n\n";
                $sqldump .= "{$field[1]};\n\n";
            }
        }
        return $sqldump;
    }

    public static function writeInfo($temp_path,$info)
    {
        $pathname = $temp_path . 'info.ini';
        $content = <<<INFO
name = $info[name]
title = $info[title]
author = $info[author]
install = $info[install]
thumb = $info[thumb]
INFO;

        if (!is_dir(dirname($pathname))) {
            mkdir(strtolower(dirname($pathname)), 0755, true);
        }
        return file_put_contents($pathname, $content);
    }
}