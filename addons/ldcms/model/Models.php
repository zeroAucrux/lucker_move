<?php

namespace addons\ldcms\model;

use addons\ldcms\model\common\Frontend;
use addons\ldcms\utils\AutoSql;
use app\common\library\Menu;
use Exception;
use think\Db;
use think\Model;
use tool\dir;


class Models extends Frontend
{
    // 表名
    protected $name = 'ldcms_models';
    protected $sort='id DESC';


    // 追加属性
    protected $append = [
        'create_time_text',
        'update_time_text',
        'status_text'
    ];

    public function getStatusTextAttr($value,$data)
    {
        $status=[0=>'关闭',1=>'启用'];
        return $status[$data['status']];
    }

    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getUpdateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['update_time']) ? $data['update_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getHomeList()
    {
        return $this->order($this->sort)->column('id,name,table_name','id');
    }
}
