<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\builder\Form;
use app\common\model\Config as ConfigModel;
use app\admin\model\ldcms\Models as ModelsModel;
use app\admin\model\ldcms\Fields as FieldsModel;
use fast\Tree;
use think\Config;
use think\Db;

/**
 * 模型字段管理
 *
 * @icon fa fa-circle-o
 */
class Fields extends Base
{

    /**
     * Fields模型对象
     * @var FieldsModel
     */
    protected $model = null;
    protected $noNeedRight = ['selectpage', 'getFields', 'getLengths'];
    protected $multiFields = 'isfilter,status,sort,islist';

    public function _initialize()
    {
        $this->loadlang('general/config');
        parent::_initialize();
        $this->model = new FieldsModel();
        $this->assignconfig('ignore_fields', ModelsModel::instance()->ignore_fields);
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function getLengths()
    {
        return $this->model->getTypeLengths();
    }

    public function getFields()
    {
        $table  = $this->request->request('table');
        $fields = $this->getTableFiles($table);
        $this->success("", null, ['fieldList' => $fields]);
    }

    protected function getTableFiles($table)
    {
        $dbname = Config::get('database.database');
        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $columnList = Db::query($sql, [$dbname, $table]);
        $fields     = [];
        foreach ($columnList as $index => $item) {
            $fields[] = ['name' => $item['COLUMN_NAME'], 'title' => $item['COLUMN_COMMENT'], 'type' => $item['DATA_TYPE']];
        }
        return $fields;
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);

        $mid         = $this->request->param('mid');
        $modelsmodel = new ModelsModel();
        $models      = $modelsmodel->getAdminList();

        if (false === $this->request->isAjax()) {
            $this->assign('models', $models);
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }

        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $list = $this->model
            ->where('mid', $mid)
            ->where($where)
            ->order($this->model->getSort())
            ->select();
        foreach ($list as &$item) {
            $item['mid_text'] = $models[$item['mid']]['name'];
        }

        $list      = collection($list)->toArray();
        $fieldList = $modelsmodel->getDocumentFields($mid);
        $list      = array_merge($list, $fieldList);

        $result = ['total' => count($list), 'rows' => $list];
        return json($result);
    }

    public function selectpage()
    {
        $id        = $this->request->get("id/d", 0);
        $fieldInfo = $this->model->get($id);
        if (!$fieldInfo) {
            $this->error("未找到指定字段");
        }
        $setting = $fieldInfo['setting'];
        if (!$setting || !isset($setting['table'])) {
            $this->error("字段配置不正确");
        }
        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        //$field = $this->request->request("showField");
        $field = $setting['field'];
        //主键
        //$primarykey = $this->request->request("keyField");
        $primarykey = $setting['primarykey'];
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        $searchfield = [$field, $primarykey];
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        $custom = isset($setting['conditions']) ? (array)json_decode($setting['conditions'], true) : [];
        //$custom = array_filter($custom);

        $admin_id = session('admin.id') ?: 0;
        $user_id  = $this->auth->id ?: 0;
        //如果是管理员需要移除user_id筛选,否则会导致管理员无法筛选列表信息
        $admin = $this->request->request("admin/d");
        if ($admin_id && $admin) {
            unset($custom['user_id']);
        } else {
            //如果不是管理员则需要判断是否开放相应的投稿字段
            if (!in_array($fieldInfo['source'], ['model', 'diyform'])) {
                $this->error("未开放栏目信息");
            }
            if (!$fieldInfo['iscontribute']) {
                $this->error("未开放字段信息");
            }
        }

        //是否返回树形结构
        $istree = $this->request->request("isTree", 0);
        $ishtml = $this->request->request("isHtml", 0);
        if ($istree) {
            $word     = [];
            $pagesize = 99999;
        }
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';
        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where    = function ($query) use ($primaryvalue, $custom, $admin_id, $user_id) {
                $query->where('id', 'in', $primaryvalue);
                if ($custom && is_array($custom)) {
                    //替换暂位符
                    $search  = ["{admin_id}", "{user_id}"];
                    $replace = [$admin_id, $user_id];
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), str_replace($search, $replace, $v[1]));
                        } else {
                            $query->where($k, '=', str_replace($search, $replace, $v));
                        }
                    }
                }
            };
            $pagesize = 99999;
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom, $admin_id, $user_id) {
                $logic       = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                $word        = array_filter($word);
                if ($word) {
                    foreach ($word as $k => $v) {
                        $query->where(str_replace(',', $logic, $searchfield), "like", "%{$v}%");
                    }
                }
                if ($custom && is_array($custom)) {
                    //替换暂位符
                    $search  = ["{admin_id}", "{user_id}"];
                    $replace = [$admin_id, $user_id];
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), str_replace($search, $replace, $v[1]));
                        } else {
                            $query->where($k, '=', str_replace($search, $replace, $v));
                        }
                    }
                }
            };
        }
        $list          = [];
        $fields        = $this->getTableFiles($setting['table']);
        $whereLang     = [];
        $documentModel = \app\admin\model\ldcms\Document::instance();

        /*如果是附表 先排查主表中的其他语言与回收站中的数据*/
        if (strpos($setting['table'], '_ldcms_document_') !== false) {
            $total = $documentModel->alias('document')
                ->join($setting['table'] . ' extend', 'document.id=extend.document_id')
                ->where(['lang' => ld_get_lang(), 'delete_time' => null])->count();
            if ($total > 0) {
                $datalist = $documentModel->alias('document')
                    ->join($setting['table'] . ' extend', 'document.id=extend.document_id')
                    ->where(['lang' => ld_get_lang(), 'delete_time' => null])
                    ->order($order)
                    ->page($page, $pagesize)
                    ->field($primarykey . "," . $field . ($istree ? ",pid" : ""))
                    ->select();

                foreach ($datalist as $index => &$item) {
                    unset($item['password'], $item['salt']);
                    $list[] = [
                        $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                        $field      => isset($item[$field]) ? $item[$field] : '',
                        'pid'       => isset($item['pid']) ? $item['pid'] : 0
                    ];
                }
                if ($istree && !$primaryvalue) {
                    $tree = Tree::instance();
                    $tree->init($list, 'pid');
                    $list = $tree->getTreeList($tree->getTreeArray(0), $field);
                    if (!$ishtml) {
                        foreach ($list as &$item) {
                            $item = str_replace('&nbsp;', ' ', $item);
                        }
                        unset($item);
                    }
                }
            }
            //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
            return json(['list' => $list, 'total' => $total]);
        }

        foreach ($fields as $item) {
            if ($item['name'] == 'lang') {
                $whereLang['lang'] = ld_get_lang();
            }
            /*防止回收站的数据被调出*/
            if ($item['name'] == 'delete_time') {
                $whereLang['delete_time'] = null;
            }
        }
        $total = Db::table($setting['table'])->where($where)->where($whereLang)->count();
        if ($total > 0) {
            $datalist = Db::table($setting['table'])->where($where)->where($whereLang)
                ->order($order)
                ->page($page, $pagesize)
                ->field($primarykey . "," . $field . ($istree ? ",pid" : ""))
                ->select();
            foreach ($datalist as $index => &$item) {
                unset($item['password'], $item['salt']);
                $list[] = [
                    $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                    $field      => isset($item[$field]) ? $item[$field] : '',
                    'pid'       => isset($item['pid']) ? $item['pid'] : 0
                ];
            }
            if ($istree && !$primaryvalue) {
                $tree = Tree::instance();
                $tree->init($list, 'pid');
                $list = $tree->getTreeList($tree->getTreeArray(0), $field);
                if (!$ishtml) {
                    foreach ($list as &$item) {
                        $item = str_replace('&nbsp;', ' ', $item);
                    }
                    unset($item);
                }
            }
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total]);
    }

    public function add()
    {
        $mid = $this->request->param('mid');
        if ($this->request->isPost()) {
            parent::addPost();
        }

        return Form::instance()
            ->setFormItem('mid', __('Mid'), 'string', '', ['class' => 'hidden', 'value' => $mid])
            ->setFormItem('title', __('Title'), 'string', 'required')
            ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
            ->setFormItem('type', __('Type'), 'select', 'required', function ($data) {
                $data['content_list'] = $this->model->getTypeList();
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class']       = 'hidden';
                $data['visible']     = 'type=selectpage,selectpages';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、关联表可以引用其他数据表的数据<br>
                    2、如果关联表有重要(隐私)数据，不建议设定为关联表，以免造成信息泄漏<br>
                </div>';
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class']       = 'hidden';
                $data['visible']     = 'type=editor';
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
                'class'   => 'hidden',
                'visible' => 'type=selectpage,selectpages',
                'setting' => ['key' => '字段名', 'value' => '字段值']
            ])
            ->setFormItem('setting.key', '键名', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('setting.value', '键值', 'string', 'required', ['class' => 'hidden', 'visible' => 'type=array'])
            ->setFormItem('content_list', __('Content'), 'text', 'required', [
                'class'       => 'hidden',
                'visible'     => 'type=checkbox,radio,select,selects',
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
     * 渲染表
     */
    protected function renderTable()
    {
        $tableList = [];
        $dbname    = \think\Config::get('database.database');
        $list      = \think\Db::query("SELECT `TABLE_NAME`,`TABLE_COMMENT` FROM `information_schema`.`TABLES` where `TABLE_SCHEMA` = '{$dbname}';");
        foreach ($list as $key => $row) {
            $tableList[$row['TABLE_NAME']] = $row['TABLE_COMMENT'] . '(' . $row['TABLE_NAME'] . ')';
        }
        return $tableList;
    }

    public function edit($ids = '')
    {
        if ($this->request->isPost()) {
            parent::editPost($ids);
        }
        $row              = $this->model->get($ids);
        $row['old_field'] = $row['field'];
        return Form::instance()
            ->setFormItem('title', __('Title'), 'string', 'required')
            ->setFormItem('old_field', '', 'string', '', ['class' => 'hidden'])
            ->setFormItem('field', __('Field'), 'string', 'required', ['placeholder' => '仅支持字母、数字、下划线'])
            ->setFormItem('type', __('Type'), 'select', 'required', function ($data) {
                $data['content_list'] = $this->model->getTypeList();
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class']       = 'hidden';
                $data['visible']     = 'type=selectpage,selectpages';
                $data['extend_html'] = '<div class="alert alert-danger-light" style="margin-bottom:0;">
                    温馨提示：<br>
                    1、关联表可以引用其他数据表的数据<br>
                    2、如果关联表有重要(隐私)数据，不建议设定为关联表，以免造成信息泄漏<br>
                </div>';
                return $data;
            })
            ->setHtml(function ($data) {
                $data['class']       = 'hidden';
                $data['visible']     = 'type=editor';
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
                'class'   => 'hidden',
                'visible' => 'type=selectpage,selectpages',
                'setting' => ['key' => '字段名', 'value' => '字段值']
            ])
            ->setFormItem('content_list', __('Content'), 'text', 'required', [
                'class'       => 'hidden',
                'visible'     => 'type=checkbox,radio,select,selects',
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

    public function multi($ids = null)
    {
        if (empty($ids)) {
            $field = $this->request->get('field');
            $mid   = $this->request->get('mid');
            parse_str($this->request->post('params'), $values);
            $values = $this->auth->isSuperAdmin() ? $values : array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
            if (empty($values)) {
                $this->error(__('You have no permission'));
            }
            $modelsmodel = new ModelsModel();
            $modelData   = $modelsmodel->get($mid);

            $documentFields = $modelsmodel->getDocumentFields($mid);
            foreach ($documentFields as &$docfield) {
                if ($docfield['field'] == $field) {
                    $docfield = array_merge($docfield, $values);
                }
            }
            $modelData->save(['default_fields' => $documentFields]);
            $this->success();
        } else {
            parent::multi($ids);
        }
    }

}