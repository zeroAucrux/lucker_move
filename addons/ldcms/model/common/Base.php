<?php


namespace addons\ldcms\model\common;


use think\Config;
use think\Db;
use think\exception\ClassNotFoundException;
use think\Model;

class Base extends Model
{
    protected $autoWriteTimestamp = 'int';
    protected $dateFormat='Y-m-d H:i:s';
    protected $sort='sort ASC,id DESC';
    protected $lang=null;
    protected static $instance=[];

    public function getSort()
    {
        return $this->sort;
    }

    public function getLang(){
        return $this->lang;
    }

    /**
     * 初始化
     * @return static
     */
    public static function instance()
    {
        $class=static::class;
        if (isset(self::$instance[$class])) {
            return self::$instance[$class];
        }
        if (!class_exists($class)) {
            throw new ClassNotFoundException('class not exists:' . $class, $class);
        }
        $model = new $class();
        return self::$instance[$class] = $model;
    }

    /**
     * 封装查询方法
     * @param $array
     * @param $field
     * @param string $op
     * @return string
     */
    public function findInSet($array,$field,$op='AND')
    {
        $where=[];
        foreach ($array as $item){
            $where[]=$this->raw("FIND_IN_SET('{$item}',{$field})");
        }
        $where=implode(" {$op} ",$where);
        return $where;
    }

    /*获取表字段*/
    public function getTableFiles($table)
    {
        $dbname = Config::get('database.database');
        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $columnList = Db::query($sql, [$dbname, $table]);
        $fields = [];
        foreach ($columnList as $index => $item) {
            $fields[] = ['name' => $item['COLUMN_NAME'], 'title' => $item['COLUMN_COMMENT'], 'type' => $item['DATA_TYPE']];
        }
        return $fields;
    }
}