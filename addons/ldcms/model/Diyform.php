<?php

namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;
use app\common\library\Menu;
use Exception;
use addons\ldcms\utils\AutoSql;
use think\Db;

class Diyform extends Frontend
{
    // 表名
    protected $name = 'ldcms_diyform';
    protected $sort = 'id DESC';

    public function fields()
    {
        return $this->hasMany(DiyformFields::class,'diyform_id');
    }
}
