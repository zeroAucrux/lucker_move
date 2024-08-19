<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\controller\Backend;
use app\admin\model\ldcms\ContentUrl as ContentUrlModel;
/**
 * 文章内容内链
 *
 * @icon fa fa-circle-o
 */
class ContentUrl extends Base
{

    /**
     * Url模型对象
     * @var \addons\ldcms\model\ContentUrl
     */
    protected $model = null;
    protected $modelValidate=true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = ContentUrlModel::instance();

    }
    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->where('lang',$this->lang)
                ->paginate($limit);
            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function add()
    {
        if($this->request->isPost()){
            $this->addPost();
        }

        return Form::instance()
            ->setFormItem('name','名称','string','required')
            ->setFormItem('url','链接','string','required')
            ->fetch();
    }

    public function edit($ids=null)
    {
        if($this->request->isPost()){
            $this->editPost($ids);
        }

        $row=$this->model->get($ids);
        return Form::instance()
            ->setFormItemHidden('id')
            ->setFormItem('name','名称','string','required')
            ->setFormItem('url','链接','string','required')
            ->values($row)
            ->fetch();
    }
}
