<?php


namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;

class Links extends Frontend
{
    protected $name='ldcms_links';

    public function getHomeList($name,$limit=null)
    {
        $result=$this
            ->where('type',$name)
            ->where('status',1)
            ->where('lang',$this->getLang())
            ->limit($limit)
            ->order($this->getSort())
            ->select();
        if(collection($result)->isEmpty()){
            return [];
        }
        return collection($result)->toArray();
    }
}