<?php


namespace addons\ldcms\controller;


use addons\ldcms\model\Category;
use addons\ldcms\model\Document;
use think\Db;
use think\Session;

class Search extends Base
{
    protected $topid=-1;
    public function index(){
        $params=$this->request->param();
        if(!$params){
            $this->error(__('Please enter keywords'));
        }
        if(isset($params['search']) && empty($params['search']) && $params['search']!=0){
            $this->error(__('Please enter keywords'));
        }

        $tpl='search';
        $search=$this->request->param('search',$this->request->param('title'));
        $search = strip_tags($search);
        $search=str_replace(strrchr($search, "."),"",$search);  //去掉带有后缀的关键词
        $search = mb_substr($search, 0, 15);

        /*指定搜索页面*/
        $searchtpl=$this->request->param('searchtpl');
        if(!empty($searchtpl)){
            $tpl=$searchtpl;
        }
        $this->view->assign('search',$search);
        $this->siteConfig['sitetitle'] =__('Search Result').'-' .$this->siteConfig['sitetitle'];
        $this->assign('ld', $this->siteConfig);
        return $this->view->fetch('/'.basename($tpl,'.html'));
    }
}