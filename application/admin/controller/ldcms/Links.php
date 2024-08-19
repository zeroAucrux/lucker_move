<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\controller\Backend;
use app\admin\model\ldcms\Links as LinksModel;

/**
 * 友情链接
 *
 * @icon fa fa-circle-o
 */
class Links extends Base
{

    /**
     * Links模型对象
     * @var LinksModel
     */
    protected $model = null;
    protected $types = null;
    protected $selectTypes=null;
    protected $searchFields='title';
    protected $noNeedRight=['selectpageType'];
    protected $modelValidate=true;


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new LinksModel();
        $this->types= $this->model->column('type');
        if($this->types){
            foreach ($this->types as $type){
                $this->selectTypes[]=['id'=>$type,'title'=>$type];
            }
        }
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

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
                ->order($this->model->getSort())
                ->paginate($limit);
            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if($this->request->isPost()){
            $this->addPost();
        }

        return Form::instance()
            ->fieldShow()
            ->setHtml('<div class="alert alert-warning-light" style="margin-bottom:0;">
                温馨提示：<br>
                如果类型不存在，可随意添加（仅支持字母、数字、下划线）
            </div>')
            ->setFormItem('type','类型','selectpage','required',['placeholder'=>'仅支持字母、数字、下划线','content_list'=>'ldcms/links/selectpageType'])
            ->setFormItem('title','标题','string','required')
            ->setFormItem('image','logo','image')
            ->setFormItem('url','链接','string','',['value'=>'#'])
            ->setFormItem('target','新窗口打开','string','',['value'=>'_blank'])
            ->setFormItem('status','状态','switch','',['value'=>1])
            ->fetch();
    }

    public function edit($ids=null)
    {
        if($this->request->isPost()){
            $this->editPost($ids);
        }

        $row=$this->model->get($ids);
        return Form::instance()
            ->setHtml('<div class="alert alert-warning-light" style="margin-bottom:0;">
                温馨提示：<br>
                如果类型不存在，可随意添加（仅支持字母、数字、下划线）
            </div>')
            ->setFormItemHidden('id')
            ->setFormItem('type','类型','selectpage','required',['placeholder'=>'仅支持字母、数字、下划线','content_list'=>'ldcms/links/selectpageType'])
            ->setFormItem('title','标题','string','required')
            ->setFormItem('image','logo','image')
            ->setFormItem('url','链接','string')
            ->setFormItem('target','新窗口打开','string')
            ->setFormItem('status','状态','switch','',['value'=>1])
            ->values($row)
            ->fetch();
    }


    public function selectpageType()
    {
        $list = [];
        $word = (array)$this->request->request("q_word/a");
        $keyValue = $this->request->request('keyValue');
        if (!$keyValue) {
            if (array_filter($word)) {
                foreach ($word as $k => $v) {
                    $list[] = ['id' => $v, 'title' => $v];
                }
            }

            $typeArr = array_unique($this->types);
            foreach ($typeArr as $index => $item) {
                $list[] = ['id' => $item, 'title' => $item];
            }
        } else {
            $list[] = ['id' => $keyValue, 'title' => $keyValue];
        }
        return json(['total' => count($list), 'list' => $list]);
    }
}
