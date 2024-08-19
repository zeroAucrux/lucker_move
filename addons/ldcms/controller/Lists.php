<?php


namespace addons\ldcms\controller;


class Lists extends Base
{
    public function index()
    {
        $template=empty($this->categoryInfo['template_list'])?$this->categoryInfo['template_detail']:$this->categoryInfo['template_list'];
        $search=$this->request->get('search');
        if($search){
            $this->view->assign('_where',['title'=>['like','%'.$search.'%']]);
        }
        /*设置栏目seo*/
        $list_title=$this->addonConfig['list_title'];
        if(!empty($list_title)){
            $list_title=$this->view->display($list_title);
            $this->siteConfig['sitetitle']=$list_title;
        }else{
            $this->siteConfig['sitetitle'] =   $this->categoryInfo['seo_title']. '-' . $this->siteConfig['sitetitle'];
        }

        if (!empty($this->categoryInfo['seo_keywords']))
            $this->siteConfig['seo_keywords'] = $this->categoryInfo['seo_keywords'];
        if (!empty($this->categoryInfo['seo_description']))
            $this->siteConfig['seo_description'] = ld_clear_html($this->categoryInfo['seo_description']);
        $this->assign('ld', $this->siteConfig);
        return $this->view->fetch('/'.basename($template,'.html'));
    }
}