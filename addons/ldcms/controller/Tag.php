<?php


namespace addons\ldcms\controller;


use addons\ldcms\model\Tags;

class Tag extends Base
{
    protected $topid=-1;
    public function index()
    {
        $tag=$this->request->param('tag');

        $title=Tags::instance()->where('name',$tag)->value('title');
        $this->view->assign('tag',$title);
        return $this->view->fetch('/tag');
    }
}