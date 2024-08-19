<?php
// +----------------------------------------------------------------------
// | LDCMS  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 http://www.zhuziweb.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: lande
// +----------------------------------------------------------------------

namespace app\admin\model\ldcms;

use think\Model;


class Tagaction extends Model
{

    // 表名
    protected $name = 'ldcms_tagaction';
    protected $autoWriteTimestamp = 'int';

    protected $type = [
        'setting' => 'json'
    ];

    // 追加属性
    protected $append = [
        'type_text',
        'create_time_text',
        'update_time_text'
    ];

    public function getTypeList()
    {
        return ['sql' => __('Sql'), 'func' => __('Func')];
    }

    public function getConditionList()
    {
        return [
            '='       => '=', '<>' => '<>', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<=',
            'LIKE'    => 'LIKE', 'NOT LIKE' => 'NOT LIKE', 'FIND_IN_SET_OR' => 'FIND_IN_SET_OR',
            'FIND_IN_SET_AND' => 'FIND_IN_SET_AND',
            'IN'      => 'IN', 'NOT IN' => 'NOT IN', 'BETWEEN' => 'BETWEEN', 'NOT BETWEEN' => 'NOT BETWEEN',
            'RANGE'   => 'RANGE', 'NOT RANGE' => 'NOT RANGE', 'NULL' => 'NULL',
            'IS NULL' => 'IS NULL', 'NOT NULL' => 'NOT NULL', 'IS NOT NULL' => 'IS NOT NULL',
            'CUSTOM'  => '自定义'
        ];

    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list  = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getUpdateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['update_time']) ? $data['update_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function setCreateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function setUpdateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}