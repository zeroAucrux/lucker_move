<?php

namespace app\admin\controller\ldcms;
use app\admin\model\ldcms\Diyform as DiyformModel;
use app\admin\model\ldcms\DiyformFields as DiyformFieldsModel;
use addons\ldcms\utils\builder\Form;
use app\common\model\Config as ConfigModel;

/**
 * 自定义字段管理
 *
 * @icon fa fa-circle-o
 */
class DiyformFields extends Base
{
    /**
     * DiyformFields模型对象
     * @var DiyformFieldsModel
     */
    protected $model = null;
    protected $multiFields='sort,status';
    public function _initialize()
    {
        $this->loadlang('general/config');
        parent::_initialize();
        $this->model = new DiyformFieldsModel();

    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);

        $diyform_id = $this->request->param('diyform_id');
        $diyform = (new DiyformModel())->getAdminList();
        if (false === $this->request->isAjax()) {
            $this->assign('diyform', $diyform);
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }

        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $list = $this->model
            ->where('diyform_id', $diyform_id)
            ->where($where)
            ->order($this->model->getSort())
            ->paginate($limit);

        $list->each(function ($item) use ($diyform) {
            $item['diyform_text'] = $diyform[$item['diyform_id']]['title'];
            return $item;
        });

        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }

    public function add()
    {
        $diyform_id = $this->request->param('diyform_id');
        if($this->request->isPost()){
            $this->addPost();
        }else{
            return Form::instance()
                ->setFormItem('diyform_id','', 'string', '', ['class' => 'hidden', 'value' => $diyform_id])
                ->setFormItem('title', __('Title'), 'string', 'required')
                ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
                ->setFormItem('type', '', 'string', 'required', ['class' => 'hidden','value'=>'string'])
                ->setFormItem('rule', __('Rule'), 'selectpage','',['content_list'=>json_encode([['id'=>'require']])])
                ->setFormItem('length', __('Length'), 'string', '', ['value'=>'100'])
                ->setFormItem('default', __('Default'), 'string')
                ->setFormItem('status', __('Status'), 'switch', '', ['value' => 1])
                ->fetch();
        }
    }

    public function edit($ids=null)
    {
        $row=$this->model->get($ids);
        $diyform_id = $this->request->param('diyform_id');
        if($this->request->isPost()) {
            $this->editPost($ids);
        }
        return Form::instance()
            ->setFormItem('diyform_id','', 'string', '', ['class' => 'hidden', 'value' => $diyform_id])
            ->setFormItem('title', __('Title'), 'string', 'required')
            ->setFormItem('old_field', '', 'string', '', ['class' => 'hidden', 'value' => $row['field']])
            ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
            ->setFormItem('rule', __('Rule'), 'selectpage','',['content_list'=>json_encode([['id'=>'require']])])
            ->setFormItem('type', '', 'string', 'required', ['class' => 'hidden','value'=>'string'])
            ->setFormItem('length', __('Length'), 'string', '', ['value'=>'100'])
            ->setFormItem('default', __('Default'), 'string')
            ->setFormItem('status', __('Status'), 'switch', '', ['value' => 1])
            ->values($row)
            ->fetch();

    }
}
