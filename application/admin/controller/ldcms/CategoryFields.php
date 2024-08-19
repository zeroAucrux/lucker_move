<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\controller\Backend;
use app\common\model\Config as ConfigModel;
use think\Config;
use think\Db;

/**
 * 栏目自定义字段管理
 *
 * @icon fa fa-circle-o
 */
class CategoryFields extends Base
{

    /**
     * CategoryFields模型对象
     * @var \app\admin\model\ldcms\CategoryFields
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ldcms\CategoryFields;
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        $prefix = \think\Config::get('database.prefix');
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $fieldList = self::getTableFiles("{$prefix}ldcms_category");


            $fields = [];
            foreach ($list as $index => $item) {
                $fields[] = $item['field'];
            }

            foreach ($fieldList as $index => $field) {
                if (in_array($field['name'], $fields)) {
                    continue;
                }
                $item = [
                    'id' => $field['name'],
                    'state' => false,
                    'name' => $field['name'],
                    'title' => $field['title'],
                    'type' => $field['type'],
                    'issystem' => true,
                    'isfilter' => 0,
                    'iscontribute' => 0,
                    'ispublish' => 0,
                    'isorder' => 0,
                    'status' => 'normal',
                    'createtime' => 0,
                    'updatetime' => 0
                ];
                $list[] = $item;
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }


    /**
     * 渲染表
     */
    protected function renderTable()
    {
        $tableList = [];
        $dbname = \think\Config::get('database.database');
        $list = \think\Db::query("SELECT `TABLE_NAME`,`TABLE_COMMENT` FROM `information_schema`.`TABLES` where `TABLE_SCHEMA` = '{$dbname}';");
        foreach ($list as $key => $row) {
            $tableList[$row['TABLE_NAME']] = $row['TABLE_COMMENT'] . '(' . $row['TABLE_NAME'] . ')';
        }
        return $tableList;
    }

    protected function getTableFiles($table)
    {
        $dbname = Config::get('database.database');
        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $columnList = Db::query($sql, [$dbname, $table]);
        $fields = [];
        foreach ($columnList as $index => $item) {
            $fields[] = ['name' => $item['COLUMN_NAME'], 'title' => $item['COLUMN_COMMENT'], 'type' => $item['DATA_TYPE']];
        }
        return $fields;
    }

    /**
     * 添加字段
     * @return string
     * @throws \think\Exception
     * Author: Wusn <958342972@qq.com>
     * DateTime: 2023/4/25 10:46
     */
    public function add()
    {
        if ($this->request->isPost()) {
            parent::addPost();
        }

        return Form::instance()
            ->setFormItem('title', __('Title'), 'string', 'required')
            ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
            ->setFormItem('type', __('Type'), 'select', 'required', function ($data) {
                $data['content_list'] = ConfigModel::getTypeList();
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class'] = 'hidden';
                $data['visible'] = 'type=selectpage,selectpages';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、关联表可以引用其他数据表的数据<br>
                    2、如果关联表有重要(隐私)数据，不建议设定为关联表，以免造成信息泄漏<br>
                </div>';
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class'] = 'hidden';
                $data['visible'] = 'type=editor';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、请确保你已经正常安装富文本编辑器插件
                </div>';
                return $data;
            })
            ->setFormItem('setting.table', __('关联表'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages', 'content_list' => $this->renderTable()])

            ->setFormItem('setting.primarykey', __('存储字段'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages'])
            ->setFormItem('setting.field', __('显示字段'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages'])
            ->setFormItem('setting.conditions', __('数据过滤'), 'array', '', [
                'class' => 'hidden',
                'visible' => 'type=selectpage,selectpages',
                'setting' => ['key' => '字段名', 'value' => '字段值']
            ])
            ->setFormItem('setting.key', '键名', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('setting.value', '键值', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('content_list', __('Content'), 'text', 'required', [
                'class' => 'hidden',
                'visible' => 'type=checkbox,radio,select,selects',
                'placeholder' => '格式`key:value`,多个请换行'
            ])
            ->setFormItem('minimum', __('Minimum'), 'number', '', ['class' => 'hidden', 'visible' => 'type=checkbox'])
            ->setFormItem('maximum', __('Maximum'), 'number', '', ['class' => 'hidden', 'visible' => 'type=checkbox,selects'])
            ->setFormItem('decimals', __('Decimals'), 'string', 'required', ['class' => 'hidden', 'visible' => 'type=number'])
            ->setFormItem('length', __('Length'), 'string', 'required', ['value' => 255])
            ->setFormItem('rule', __('Rule'), 'string')
            ->setFormItem('tip', __('Tip'), 'string')
            ->setFormItem('default', __('Default'), 'string')
            ->setFormItem('status', __('Status'), 'switch', '', ['value' => 1])
            ->setFormItem('islist', __('是否数据列表显示'), 'switch', '', ['value' => 1])
            ->setFormItem('visible', __('动态显示'), 'string', '', ['tip' => '用于设定表单name等于某一个值时显示，例如：type=1,那么当type字段的值为1时，当前字段会显示'])
            ->setFormItem('extend_html', __('Extend'), 'text', '', ['placeholder' => '用于扩展html标签中的 data-xx 属性'])
            ->fetch();
    }

    /**
     * 更新字段
     * @param string $ids
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * Author: Wusn <958342972@qq.com>
     * DateTime: 2023/4/25 10:46
     */
    public function edit($ids = '')
    {
        if ($this->request->isPost()) {
            parent::editPost($ids);
        }
        $row = $this->model->get($ids);
        $row['old_field'] = $row['field'];
        return Form::instance()
            ->setFormItem('title', __('Title'), 'string', 'required')
            ->setFormItem('old_field', '', 'string', '', ['class' => 'hidden', 'value' => $row['field']])
            ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
            ->setFormItem('type', __('Type'), 'select', 'required', function ($data) {
                $data['content_list'] = ConfigModel::getTypeList();
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class'] = 'hidden';
                $data['visible'] = 'type=selectpage,selectpages';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、关联表可以引用其他数据表的数据<br>
                    2、如果关联表有重要(隐私)数据，不建议设定为关联表，以免造成信息泄漏<br>
                </div>';
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class'] = 'hidden';
                $data['visible'] = 'type=editor';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、请确保你已经正常安装富文本编辑器插件
                </div>';
                return $data;
            })
            ->setFormItem('setting.table', __('关联表'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages', 'content_list' => $this->renderTable()])
            ->setFormItem('setting.primarykey', __('存储字段'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages'])
            ->setFormItem('setting.field', __('显示字段'), 'select', 'required', ['class' => 'hidden', 'visible' => 'type=selectpage,selectpages'])
            ->setFormItem('setting.key', '键名', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('setting.value', '键值', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('setting.conditions', __('数据过滤'), 'array', '', [
                'class' => 'hidden',
                'visible' => 'type=selectpage,selectpages',
                'setting' => ['key' => '字段名', 'value' => '字段值']
            ])
            ->setFormItem('content_list', __('Content'), 'text', 'required', [
                'class' => 'hidden',
                'visible' => 'type=checkbox,radio,select,selects',
                'placeholder' => '格式`key:value`,多个请换行'
            ])
            ->setFormItem('minimum', __('Minimum'), 'number', '', ['class' => 'hidden', 'visible' => 'type=checkbox'])
            ->setFormItem('maximum', __('Maximum'), 'number', '', ['class' => 'hidden', 'visible' => 'type=checkbox,selects'])
            ->setFormItem('decimals', __('Decimals'), 'string', 'required', ['class' => 'hidden', 'visible' => 'type=number'])
            ->setFormItem('length', __('Length'), 'string', 'required')
            ->setFormItem('rule', __('Rule'), 'string')
            ->setFormItem('tip', __('Tip'), 'string')
            ->setFormItem('default', __('Default'), 'string')
            ->setFormItem('islist', __('是否数据列表显示'), 'switch', '', ['value' => 1])
            ->setFormItem('visible', __('动态显示'), 'string', '', ['tip' => '用于设定表单name等于某一个值时显示，例如：type=1,那么当type字段的值为1时，当前字段会显示'])
            ->setFormItem('status', __('Status'), 'switch', '', ['value' => 1])
            ->values($row)
            ->fetch();
    }
}
