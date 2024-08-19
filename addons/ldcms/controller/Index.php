<?php


namespace addons\ldcms\controller;


class Index extends Base
{
    public function index()
    {
        $index_title=$this->addonConfig['index_title'];
        if(!empty($index_title)){
            $index_title=$this->view->display($index_title);
            $this->siteConfig['sitetitle']=$index_title;
            $this->assign('ld', $this->siteConfig);
        }

        return $this->view->fetch('/index');
    }

}