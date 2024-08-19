<?php

namespace app\admin\controller\ldcms;
use addons\ldcms\utils\builder\Form;
use app\admin\model\ldcms\Diyform as DiyformModel;

/**
 * 自定义表单管理
 *
 * @icon fa fa-circle-o
 */
class Diyform extends Base
{

    /**
     * Diyform模型对象
     * @var DiyformModel
     */
    protected $model = null;
    protected $searchFields='title';
    protected $multiFields='status,sort,needlogin,iscaptcha';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new DiyformModel();
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->addPost();
        } else {
            return Form::instance()
                ->setFormItem('name', '表单名称', 'string', 'required',['placeholder'=>'仅支持字母、数字、下划线'])
                ->setFormItem('title', '标题', 'string', 'required')
                ->setFormItem('table', '表名', 'string', 'required',['placeholder'=>'仅支持字母、数字、下划线'])
                ->setFormItem('needlogin', '是否需要登录', 'switch')
                ->setFormItem('iscaptcha', '是否开启验证码', 'switch')
                ->setFormItem('status', '状态', 'switch', '', ['value' => 1])
                ->fetch();
        }
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if ($this->request->isPost()) {
            $this->editPost($ids);
        } else {
            return Form::instance()
                ->setFormItem('name', '表单名称', 'string', 'required',['placeholder'=>'仅支持字母、数字、下划线'])
                ->setFormItem('title', '标题', 'string', 'required')
                ->setFormItem('table', '表名', 'string', 'required',['placeholder'=>'仅支持字母、数字、下划线'])
                ->setFormItem('needlogin', '是否需要登录', 'switch')
                ->setFormItem('iscaptcha', '是否开启验证码', 'switch')
                ->setFormItem('status', '状态', 'switch', '', ['value' => 1])
                ->values($row)
                ->fetch();
        }
    }

}
