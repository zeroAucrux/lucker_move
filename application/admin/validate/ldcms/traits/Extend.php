<?php


namespace app\admin\validate\ldcms\traits;


use think\Db;
use think\exception\ClassNotFoundException;
use think\Loader;

trait Extend
{
    /**
     * 多语言验证是否唯一
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 格式：数据表,字段名,排除ID,主键名
     * @param array     $data  数据
     * @param string    $field  验证字段名
     * @return bool
     */
    protected function uniqueLang($value, $rule, $data, $field)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
            try {
                $db = Loader::model($rule[0]);
            } catch (ClassNotFoundException $e) {
                $db = Db::name($rule[0]);
            }
        }
        $key = isset($rule[1]) ? $rule[1] : $field;

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                if (isset($data[$key])) {
                    $map[$key] = $data[$key];
                }
            }
        } elseif (strpos($key, '=')) {
            parse_str($key, $map);
        } elseif (isset($data[$field])) {
            $map[$key] = $data[$field];
        } else {
            $map = [];
        }

        $pk = isset($rule[3]) ? $rule[3] : $db->getPk();
        if (is_string($pk)) {
            if (isset($rule[2])) {
                $map[$pk] = ['neq', $rule[2]];
            } elseif (isset($data[$pk])) {
                $map[$pk] = ['neq', $data[$pk]];
            }
        }
        $map['lang'] = ['=', ld_get_lang()];
        if ($db->where($map)->field($pk)->find()) {
            return false;
        }
        return true;
    }
}