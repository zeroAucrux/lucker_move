<?php

namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;
use think\View;

class Ad extends Frontend
{
    // 表名
    protected $name = 'ldcms_ad';


    public function getImageAttr($value, $data)
    {
        if (empty($value)) {
            return cdnurl(config('ldcms.slider_noimage'),true);
        }
        return cdnurl($value, true);
    }

    public function getContentAttr($value, $data)
    {
        $view = View::instance();
        $view->engine->layout(false);
        $view->assign('item',$data);
        return $view->display($data['content']);
    }

    /**
     * 获取指定类型的幻灯片
     * @param string $name     指定分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHomeSlide($name)
    {
        $result=$this
            ->where('type',$name)
            ->where('status',1)
            ->where('lang',$this->getLang())
            ->order($this->getSort())
            ->select();
        if(collection($result)->isEmpty()){
            return [];
        }

        return collection($result);
    }
}
