<?php


namespace app\admin\controller\ldcms;


use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use Exception;

class Base extends Backend
{
    protected $lang = null;
    protected $userinfo = null;
    protected $noNeedRight = [];
    protected $multiFields = 'sort,status';
    protected $addon_config=[];
    public function _initialize()
    {
        parent::_initialize();
        $this->lang = ld_get_lang();

        /*多语言*/
        if ($this->request->param('ldcms_lang')) {
            $lang = $this->request->param('ldcms_lang');
            ld_set_lang('backend_language', $lang);
            $this->lang = $lang;
        }

        $this->userinfo = $this->auth->getUserInfo();
        $this->assignconfig('ldcmslang', $this->lang);
        $this->addon_config=get_addon_config('ldcms');
        $this->assign('addon_config',$this->addon_config );
    }

    protected function addPost()
    {
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $params['lang'] = $this->lang;
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\controller\\", "\\validate\\", get_class($this));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException | PDOException | Exception $e) {

            /*  zhangji 2024-08-10
             *  这段代码是以前的，即，直接回滚
             *  Db::rollback();
             *  $this->error($e->getMessage());
             */
            //2024-08-10 修复PHP8下PDO异常信息不一致导致事务回滚失败
            $no_active_transaction = $e->getMessage() !== null ? $e->getMessage(): '';
            $php8_pdo_no_active_transaction = version_compare(PHP_VERSION, '8.0.0', '>') && $no_active_transaction === 'There is no active transaction';
            if(!$php8_pdo_no_active_transaction){
                Db::rollback();
                $this->error($e->getMessage());
            } else {
               $result = true;
            }
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }

    protected function editPost($ids)
    {
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $row = $this->model->get($ids);
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
            /*  zhangji 2024-08-10
             *  这段代码是以前的，即，直接回滚
             *  Db::rollback();
             *  $this->error($e->getMessage());
             */
            //2024-08-10 修复PHP8下PDO异常信息不一致导致事务回滚失败
            $no_active_transaction = $e->getMessage() !== null ? $e->getMessage(): '';
            $php8_pdo_no_active_transaction = version_compare(PHP_VERSION, '8.0.0', '>') && $no_active_transaction === 'There is no active transaction';
            if(!$php8_pdo_no_active_transaction){
                Db::rollback();
                $this->error($e->getMessage());
            } else {
                $result = true;
            }
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

    public function getUserLevels()
    {
        $where = [];
        if ($this->request->request('searchKey')) {
            $where['level'] = ['in', $this->request->request('searchValue')];
        }
        $levels = Db::name('user')
            ->where('status', 'normal')
            ->where($where)->field('level')->group('level')->select();
        $data = [];
        foreach ($levels as $level) {
            $data[] = ['id' => $level['level'], 'title' => '会员等级' . $level['level']];
        }
        return json(['list' => $data, 'total' => count($data)]);
    }
}