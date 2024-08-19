<?php


namespace app\admin\controller\ldcms;


use addons\ldcms\utils\ThemeService;
use fast\Http;
use think\Config;
use think\Exception;
use ZipArchive;

class Themes extends Base
{

    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            /*获取模板*/
            $themes=ThemeService::getThemeList();
            /*获取语言*/
            $langs=config('ldcms.langs');
            /*获取语言对应使用的模板*/
            $langtheme=[];
            foreach ($langs as $lk=>$lang){
                $langconfig=ld_get_site_config($lk,true);
                $langtheme[$langconfig['template']]=['template'=>$langconfig['template'],'key'=>$lk,'text'=>$lang];
            }
            $list=[];
            foreach ($themes as $k=>$v){
                if($v['install']==0){
                    $v['thumb']='/assets/addons/ldcms/admin/img/noinstall.png?v='.time();
                }else{
                    $v['thumb']='/assets/addons/ldcms/admin/img/'.$v['thumb'];
                }
                /*判断模板是否被使用 并获取使用模板的语言*/
                foreach ($langtheme as $lk=>$lang){
                    if($lk==$v['name']){
                        $v['lang_text']=$lang['text'];
                        $v['lang']=$lang['key'];
                        $v['state']=1;
                    }
                }
                $list[]=$v;
            }
            $result = array("total" =>count($list), "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /*上传安装*/
    public function local()
    {
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        try {
            ThemeService::local($file);
        } catch (Exception $e) {
            $this->error(__($e->getMessage()));
        }
        $this->success(__('Offline installed tips'));
    }

    /*安装*/
    public function install()
    {
        if($this->request->isAjax()){
            $name=$this->request->param('name');
            try {
                ThemeService::install($name);
            } catch (Exception $e) {
                $this->error(__($e->getMessage()));
            }
            $this->success(__('Offline installed tips'));
        }
    }

    /*启用*/
    public function enable()
    {
        $name=$this->request->param('name');
        $title=$this->request->param('title');
        $is_testdata=$this->request->param('is_testdata');
        if ($this->request->isPost()){
//            $lang=$this->request->param('lg','zh-cn');
//            $config=ld_get_site_fullconfig($lang);
            $is_import=$this->request->post('is_import');

//            foreach ($config as &$conf){
//                if ($conf['name']=='template'){
//                    $conf['value']=$name;
//                }
//            }

            /*复制静态资源*/
            $sourceAssetsDir = ThemeService::getSourceAssetsDir($name);
            $destAssetsDir = ThemeService::getDestAssetsDir($name);
            if (is_dir($sourceAssetsDir)) {
                copydirs($sourceAssetsDir, $destAssetsDir);
                /*删除资源目录*/
                @rmdirs($sourceAssetsDir);
            }

            /*复制 uploads 数据*/
            $sourceUploadsDir = ThemeService::getSourceUploadsDir($name);
            $destUploadsDir = ThemeService::getDestUploadsDir($name);
            if (is_dir($sourceUploadsDir)) {
                copydirs($sourceUploadsDir, $destUploadsDir);
            }

            /*导入数据文件*/
            if($is_import){
                ThemeService::importsql($name);
            }

            /*获取导入的站点信息*/
//            ld_get_site_fullconfig($lang,true);
//            ld_set_site_fullconfig($config,$lang);

            $this->success('启用成功');
        }else{
            $langs=config('ldcms.langs');
            $this->view->assign('langs',$langs);
            $this->view->assign('name',$name);
            $this->view->assign('title',$title);
            $this->view->assign('is_testdata',$is_testdata);
            return $this->view->fetch();
        }
    }

    /*下载模板*/
    public function download()
    {
        $name=$this->request->param('name');
        $lang=$this->request->param('lg');
        /*备份数据*/
        $sql=ThemeService::backupldcms($lang);
        /*打包模板*/

        /*先复制 public 中的静态资源*/
        $destAssetsDir = ThemeService::getDestAssetsDir($name);
        $sourceAssetsDir = ThemeService::getSourceAssetsDir($name);
        if (is_dir($destAssetsDir)) {
            copydirs($destAssetsDir, $sourceAssetsDir);
        }

        $zip = new ZipArchive();
        $backUpdir = ThemeService::getThemesBackupDir();
        if (!is_dir($backUpdir)) {
            @mkdir($backUpdir, 0755);
        }
        $back_name = "ldcms-{$name}";
        $filename = $backUpdir . $back_name . ".zip";

        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== true) {
            throw new Exception("Could not open <$filename>\n");
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(ThemeService::getThemesDir($name)), \RecursiveIteratorIterator::LEAVES_ONLY);
        $is_ini = false;
        foreach ($files as $k => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = str_replace(DS, '/', substr($filePath, strlen(ThemeService::getThemesDir($name))));
                /*修改 info.ini*/
                if($relativePath=='info.ini'){
                    $info=ThemeService::getInfo($name);
                    $info['install']=0;
                    $res = array();
                    foreach ($info as $key => $val) {
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
                    $zip->addFromString('info.ini', implode("\n", $res) . "\n");
                    $is_ini=true;
                }else{
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }

        if(!$is_ini&& in_array($name,['default','en'])){
            $info=[
                'name'=>$name,
                'title'=>$name=='default'?'默认中文模板':'默认英文模板',
                'author'=>'ldcms',
                'thumb'=>$name.'.png',
                'install'=>0,
            ];
            $res = array();
            foreach ($info as $key => $val) {
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
            $zip->addFromString('info.ini', implode("\n", $res) . "\n");
        }

        $sqlname="testdata.sql";
        $zip->addFromString($sqlname, $sql);
        $zip->close();
        /*删除资源目录*/
        @rmdirs($sourceAssetsDir);
        /*下载*/
        Http::sendToBrowser($filename);
    }
}