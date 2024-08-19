<?php


namespace addons\ldcms\controller;


use addons\ldcms\model\Document;

class Detail extends Base
{
    public function index()
    {
        if (empty($this->categoryInfo['template_detail'])) {
            /*模板不存在*/
            abort(500, __('Template does not exist') . "：{$this->categoryInfo['template_detail']}");
        }

        /*内容*/
        $id = $this->request->param('id');
        if (!empty($id)) {
            $this->contentInfo = Document::instance()->getHomeInfoById($id);
            if (!empty($this->contentInfo['gid'])) {
                $this->checkAuth($this->contentInfo['gid']);
            }
            if (empty($this->contentInfo)) {
                abort(404, __("Not found"));
            }
            $this->assign('content', $this->contentInfo);
            /*设置seo*/

            $detail_title=$this->addonConfig['detail_title'];
            if(!empty($detail_title)){
                $detail_title=$this->view->display($detail_title);
                $this->siteConfig['sitetitle']=$detail_title;
            }else{
                $this->siteConfig['sitetitle'] = $this->contentInfo['title'] . '-'.$this->categoryInfo['seo_title']. '-'  . $this->siteConfig['sitetitle'];
            }

            if (!empty($this->contentInfo['seo_keywords']))
                $this->siteConfig['seo_keywords'] = $this->contentInfo['seo_keywords'];
            if (!empty($this->contentInfo['seo_description']))
                $this->siteConfig['seo_description'] = ld_clear_html($this->contentInfo['seo_description']);
            $this->assign('ld', $this->siteConfig);
        }

        $custom_tpl=isset($this->contentInfo['custom_tpl'])&&!empty($this->contentInfo['custom_tpl'])?$this->contentInfo['custom_tpl']:false;
        $tpl=$custom_tpl?:$this->categoryInfo['template_detail'];
        return $this->view->fetch('/' . basename($tpl, '.html'));
    }
}