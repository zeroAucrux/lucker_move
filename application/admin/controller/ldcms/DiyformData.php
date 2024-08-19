<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\controller\Backend;
use app\admin\model\ldcms\Diyform as DiyformModel;
use app\admin\model\ldcms\DiyformData as DiyformDataModel;
use app\admin\model\ldcms\DiyformFields as DiyformFieldsModel;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * message
 *
 * @icon fa fa-circle-o
 */
class DiyformData extends Base
{

    /**
     * DiyformData模型对象
     * @var \addons\ldcms\model\DiyformData
     */
    protected $model = null;
    protected $fields = null;
    protected $diyform_id=0;

    public function _initialize()
    {
        parent::_initialize();
        $diyform_id=$this->request->param('diyform_id');
        $diyformData=DiyformModel::instance()->where('id',$diyform_id)->find();
        if(empty($diyformData['table'])){
            throw new Exception('自定义表单不存在');
        }
        $this->model = (new DiyformDataModel())->name($diyformData['table']);
        $this->assign('diyform',$diyformData);
        $this->fields =(new DiyformFieldsModel())->getAdminList($diyform_id);
        $this->assignconfig('fields', $this->fields);
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
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }

        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $list = $this->model
            ->alias('diyform_data')
            ->where($where)
            ->where('lang',$this->lang)
            ->order(DiyformDataModel::instance()->getSort())
            ->paginate($limit);

        $result = array("total" => $list->total(), "rows" => $list->items());

        return json($result);
    }

    public function edit($ids = null)
    {
        $row = $this->model->find($ids);
        if ($this->request->isPost()) {
            $params = $this->request->post('row/a');
            if (empty($params)) {
                $this->error(__('Parameter %s can not be empty', ''));
            }

            if (!$row) {
                $this->error(__('No Results were found'));
            }

            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }

            $params = $this->preExcludeFields($params);
            $result = false;
            Db::startTrans();
            try {
                //是否采用模型验证
                if ($this->modelValidate) {
                    $name = str_replace("\\controller\\", "\\validate\\", get_class($this));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                    $row->validateFailException()->validate($validate);
                }
                $result = $row->allowField(true)->save($params);
                Db::commit();
            } catch (ValidateException | PDOException | Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if (false === $result) {
                $this->error(__('No rows were updated'));
            }
            $this->success();
        } else {
            return Form::instance()
                ->setFormItems($this->fields)
                ->setFormItem('user_ip','IP','string')
                ->setFormItem('user_os','系统','string')
                ->setFormItem('user_bs','浏览器','string')
                ->values($row)
                ->fetch();
        }
    }
}
