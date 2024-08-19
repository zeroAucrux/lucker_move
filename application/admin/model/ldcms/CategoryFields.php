<?php

namespace app\admin\model\ldcms;

use addons\ldcms\model\common\Backend;
use addons\ldcms\utils\AutoSql;
use think\Exception;
use think\exception\PDOException;
use think\Model;


class CategoryFields extends Backend
{
    // 表名
    protected $name = 'ldcms_category_fields';

    protected $type=[
        'setting'=>'array'
    ];

    // 追加属性
    protected $append = [
        'create_time_text',
        'update_time_text',
//        'content_list_arr'
    ];


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

    protected function setCreateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setUpdateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected static function init() {
        /*新增前回调*/
        self::beforeInsert(function ($data) {
            $data['field'] = trim($data['field']);
            self::validateField($data);
            self::writeField($data,'add');
        });

        /*更新前回调*/
        self::beforeUpdate(function ($data){
            $data['field'] = trim($data['field']);
            self::validateField($data);
            self::writeField($data,'edit');
        });

        self::beforeDelete(function ($data){
            self::writeField($data,'delete');
        });
    }

    /**
     * 验证字段是否合规
     */
    protected static function validateField($data){
        $changedData = $data->getChangedData();
        if (isset($changedData['field'])) {
            if (!preg_match("/^([a-zA-Z0-9_]+)$/i", $data['field'])) {
                throw new Exception("字段只支持字母数字下划线");
            }
            if (is_numeric(substr($data['field'], 0, 1))) {
                throw new Exception("字段不能以数字开始");
            }
            $tableFields = \think\Db::name('ldcms_category')->getTableFields();
            if (in_array(strtolower($data['field']), $tableFields)) {
                throw new Exception("字段已经在主表存在了");
            }
            if (in_array($data['field'], ['id', 'content'])) {
                throw new Exception("字段已经存在");
            }

            $tableFields = ['id', 'user_id', 'type', 'create_time', 'update_time','delete_time'];
            if (in_array(strtolower($data['field']), $tableFields)) {
                throw new Exception("字段为保留字段，请使用其它字段");
            }

            $vars = array_keys(get_class_vars('\think\Model'));
            $vars = array_map('strtolower', $vars);
            $vars = array_merge($vars, ['url', 'fullurl']);
            if (in_array(strtolower($data['field']), $vars)) {
                throw new Exception("字段为模型保留字段，请使用其它字段");
            }
            return true;
        }else{
            return false;
        }
    }

    /*写入字段*/
    public static function writeField($data, $type = 'add')
    {
        $autoSql = AutoSql::instance();
        $table='ldcms_category';
        if($type=='delete'){
            $sql= $autoSql->setTable($table)->setField($data['field'])->getSql($type);
        }else{
            if (isset($data['old_field']) && $data['old_field'] != $data['field']) {
                $autoSql->setOldField($data['old_field']);
            }

            $autoSql->setTable($table)
                ->setField($data['field'])
                ->setType($data['type'])
                ->setLength($data['length'])
                ->setComment($data['title']);
            if(isset($data['decimals'])){
                $autoSql->setDecimals($data['decimals']);
            }
            $sql= $autoSql->setDefault($data['default'])
                ->getSql($type);
        }
        try {
            db()->execute($sql);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 获取栏目扩展字段列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: Wusn <958342972@qq.com>
     * DateTime: 2023/4/25 11:59
     */
    public function getFieldsList(){
        $res = $this->where('status',1)
            ->order($this->sort)
            ->field('update_time,status,sort,create_time',true)
            ->select();

        $res=collection($res);
        if($res->isEmpty()){
            return [];
        }
        return $res->toArray();
    }
}
