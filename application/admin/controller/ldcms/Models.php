<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\controller\Backend;
use app\admin\model\ldcms\Models as ModelsModel;

/**
 * cms 模型管理
 *
 * @icon fa fa-circle-o
 */
class Models extends Base
{

    /**
     * Models模型对象
     * @var ModelsModel
     */
    protected $model = null;
    protected $searchFields='name';
    protected $multiFields="ismenu,status";
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new ModelsModel();
        $this->assignconfig('customColor', $this->model::getCustomColor());
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function add()
    {
        if($this->request->isPost()){
            parent::addPost();
        }

        return Form::instance()
            ->setHtml('<div class="alert alert-warning-light">
        <b>对应表如何填写？</b><br>
假设：需要新增一个新闻模型，那么对应表只需填写news即可。<br>不需要填写前缀，最终会生成<code>fa_ldcms_document_news</code>表。
    </div>')
            ->setFormItem('name','模型名','string','required')
            ->setFormItem('table_name','对应表','string','required')
            ->setFormItem('template_list','列表模板','selectpage','',function ($data){
                $data['content_list']=json_encode($this->model->getTpl('list'));
                return $data;
            })
            ->setFormItem('template_detail','内容模板','selectpage','',function ($data){
                $data['content_list']=json_encode($this->model->getTpl('detail'));
                return $data;
            })
            ->setFormItem('ismenu','后台菜单','switch')
            ->setFormItem('status','状态','switch','',['value'=>1])
            ->fetch();
    }

    public function edit($ids=null)
    {
        if($this->request->isPost()){
            parent::editPost($ids);
        }

        $row = $this->model->get($ids);
        return Form::instance()
            ->setHtml('<div class="alert alert-warning-light">
        <b>对应表如何填写？</b><br>
假设：需要新增一个新闻模型，那么对应表只需填写news即可。<br>不需要填写前缀，最终会生成<code>fa_ldcms_document_news</code>表。
    </div>')
            ->setFormItem('name','模型名','string','required')
            ->setFormItem('table_name','对应表','string','required',function ($data){
                $data['extend_html']='readonly';
                return $data;
            })
            ->setFormItem('template_list','列表模板','selectpage','',function ($data){
                $data['content_list']=json_encode($this->model->getTpl('list'));
                return $data;
            })
            ->setFormItem('template_detail','内容模板','selectpage','',function ($data){
                $data['content_list']=json_encode($this->model->getTpl('detail'));
                return $data;
            })
            ->setFormItem('ismenu','后台菜单','switch')
            ->setFormItem('status','状态','switch')
            ->values($row)
            ->fetch();
    }

    public function del($ids=null)
    {
        if(empty($ids)){
            $this->error('请选择数据');
        }
        $notid=[1];
        $ids=explode(',',$ids);
        if(array_intersect($notid,$ids)){
            $this->error('单页模型禁止删除');
        }
        parent::del($ids);
    }
}
