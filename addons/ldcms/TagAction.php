<?php

namespace addons\ldcms;

use think\Loader;

class TagAction
{
    //单例模式
    private static $instance;
    protected $model = null;
    protected $row = null;

    public function __construct()
    {
        $this->model = \addons\ldcms\model\TagAction::instance();
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function setRow($row)
    {
        $this->row = $row;
        return $this;
    }

    protected function buildparams($model, $aliasName, $params)
    {
        $filter = $params['filter'];
        $op     = $params['op'];
        $logic  = [];
        $where  = [];
        $bind   = [];

        $index = 0;
        foreach ($filter as $k => $v) {
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $k)) {
                continue;
            }
            $field = $k;
            $sym   = $op[$k] ?? '=';
            if (stripos($k, ".") === false) {
                $k = $aliasName . $k;
            }
            $v             = !is_array($v) ? trim($v) : $v;
            $sym           = strtoupper($op[$k] ?? $sym);
            $logic[$index] = [$k, $params['logic'][$field]];
            //null和空字符串特殊处理
            if (!is_array($v)) {
                if (in_array(strtoupper($v), ['NULL', 'NOT NULL'])) {
                    $sym = strtoupper($v);
                }
                if (in_array($v, ['""', "''"])) {
                    $v   = '';
                    $sym = '=';
                }
            }

            switch ($sym) {
                case '=':
                case '<>':
                    $where[] = [$k, $sym, (string)$v];
                    break;
                case 'LIKE':
                case 'NOT LIKE':
                case 'LIKE %...%':
                case 'NOT LIKE %...%':
                    $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    $where[] = [$k, $sym, intval($v)];
                    break;
                case 'FINDIN':
                case 'FINDINSET':
                case 'FIND_IN_SET_OR':
                    $v             = is_array($v) ? $v : explode(',', str_replace(' ', ',', $v));
                    $findArr       = array_values($v);
                    $where_find_in = [];
                    foreach ($findArr as $idx => $item) {
                        $bindName        = "item_" . $index . "_" . $idx;
                        $bind[$bindName] = $item;
                        $where_find_in[] = "FIND_IN_SET(:{$bindName}, `" . str_replace('.', '`.`', $k) . "`)";
                    }
                    $where[] = implode(" OR ", $where_find_in);
                    break;
                case 'FIND_IN_SET_AND':
                    $v             = is_array($v) ? $v : explode(',', str_replace(' ', ',', $v));
                    $findArr       = array_values($v);
                    $where_find_in = [];
                    foreach ($findArr as $idx => $item) {
                        $bindName        = "item_" . $index . "_" . $idx;
                        $bind[$bindName] = $item;
                        $where_find_in[] = "FIND_IN_SET(:{$bindName}, `" . str_replace('.', '`.`', $k) . "`)";
                    }
                    $where[] = implode(" AND ", $where_find_in);
                    break;
                case 'IN':
                case 'IN(...)':
                case 'NOT IN':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr, function ($v) {
                            return $v != '' && $v !== false && $v !== null;
                        })) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'BETWEEN' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'BETWEEN' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, $sym, $arr];
                    break;
                case 'RANGE':
                case 'NOT RANGE':
                    $v   = str_replace(' - ', ',', $v);
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'RANGE' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'RANGE' ? '>=' : '<';
                        $arr = $arr[0];
                    }

                    $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' TIME', $arr];
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                case 'CUSTOM':
                    $where[] = $v;
                    break;
                default:
                    break;
            }
            $index++;
        }
        $where = function ($query) use ($where, $bind, &$model, $logic) {
            if (!empty($model)) {
                $model->bind($bind);
            }
            foreach ($where as $k => $v) {
                $logic_item = $logic[$k];
                if (is_array($v)) {
                    if ($logic_item[1] == 'AND') {
                        call_user_func_array([$query, 'where'], $v);
                    } else {
                        call_user_func_array([$query, 'whereOr'], $v);
                    }
                } else {
                    if ($logic_item[1] == 'AND') {
                        $query->where($v);
                    } else {
                        $query->whereOr($v);
                    }
                }
            }
        };
        return [$where, $bind];
    }

    /*exec 执行*/
    public function exec($params)
    {
        $action = $params['action'];
        $row    = $this->model->where('action', $action)->find();
        if (!$row) {
            return false;
        }
        $this->row = $row;
        if ($this->row['type'] == 'sql') {
            return $this->sql($params, $params['buildsql'] ?? false);
        } else {
            return $this->func($params);
        }
    }

    /*sql*/
    public function sql($params, $buildsql = false)
    {
        $row    = $this->row;
        $simple = $params['simple'] ?? null;
        $page   = $params['page'] ?? false;
        $offset = $params['offset'] ?? 0;
        $limit  = $params['limit'] ?? null;
        $order  = $params['order'] ?? '';

        unset($params['action']);
        unset($params['simple']);
        unset($params['page']);
        unset($params['offset']);
        unset($params['limit']);
        unset($params['order']);

        //判断手机端是否开启简洁分页
        $addon_config=get_addon_config('ldcms');
        $simple= is_null($simple)&& $addon_config['simple']&&request()->isMobile();

        $setting = $row['setting']; $model = '';
        $alias = $setting['alias'];
        if ($setting['db_type'] == 'model') {
            $model = model($setting['model']);
            /*判断class 下的方法是否存在*/
            if (method_exists($model, 'getSort')) {
                $order = $order ?: $model->getSort();
            }
            $model = $model->alias($alias);
        } elseif ($setting['db_type'] == 'name') {
            $model = \think\Db::name($setting['name'])->alias($alias);
        }

        /*排序*/
        if (!$order) {
            $order = 'id desc';
        }

        $filter = [];
        $op     = [];
        $logic  = [];
        foreach ($setting['params'] as $key => $item) {
            $field = $item[0];
            /*参数 与默认值 都没有时不查询*/
            if (!isset($params[$field]) && !isset($item[2])) {
                continue;
            }
            $value          = $params[$field] ?? $item[2];
            $value          = $this->autoBuildFunc($value);
            $filter[$field] = is_string($value) ? trim($value) : $value;
            $op[$field]     = trim($item[1]);
            $logic[$field]  = $item[3];
        }

        $params['filter'] = $filter;
        $params['op']     = $op;
        $params['logic']  = $logic;
        list($where, $bind) = $this->buildparams($model, $alias . '.', $params);

        $listDb = $model
            ->where($where)
            ->join($setting['join'])
            ->field($setting['field'])
            ->order($order);

        if ($buildsql) {
            return $listDb->limit($offset, $limit)->fetchSql($buildsql)->select();
        }

        //是否开启分页
        if ($page) {
            $result = $listDb->paginate($limit, $simple, [
                'type'  => '\addons\ldcms\utils\Bootstrap',
                'query' => request()->get()
            ]);
        } else {
            $result = $listDb->limit($offset, $limit)->select();
        }

        return $result;
    }

    /*函数*/
    public function func($params)
    {
        $class   = null;
        $row     = $this->row;
        $setting = $row['setting'];

        foreach ($setting['params'] as $key => $item) {
            $field = $item[0];
            /*参数 与默认值 都没有时不查询*/
            if (!isset($params[$field]) && !isset($item[2])) {
                continue;
            }
            $value          = $params[$field] ?? $item[2];
            $value          = $this->autoBuildFunc($value);
            $params[$field] = trim($value);
        }

        if (isset($setting['class']) && !empty($setting['class'])) {
            $class = Loader::model($setting['class']);
        }
        if ($class) {
            $res = call_user_func_array([$class, $setting['func']], [$params]);
        } else {
            $res = call_user_func_array($setting['func'], [$params]);
        }
        return $res;
    }

    /*调用函数*/
    public function autoBuildFunc($name)
    {
        $flag = substr($name, 0, 1);
        if (':' == $flag) {
            // 以:开头为函数调用，解析前去掉:
            $name = substr($name, 1);
            $name = preg_replace('/\(\)/', '', $name);
            $name = call_user_func($name);
        }

        if (defined($name)) {
            return $name;
        }
        return $name;
    }
}