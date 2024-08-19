<?php

namespace app\admin\model\ldcms;

use addons\ldcms\model\common\Backend;
use app\admin\model\ldcms\Diyform as DiyformModel;
use addons\ldcms\utils\AutoSql;
use Exception;
use think\exception\PDOException;

class DiyformFields extends Backend
{
    // 表名
    protected $name = 'ldcms_diyform_fields';
    protected $sort = 'sort ASC,id DESC';

    protected static function init() {
        /*新增前回调*/
        self::beforeInsert(function ($data) {
            self::validateField($data);
            self::writeField($data,'add');
        });

        /*更新前回调*/
        self::beforeUpdate(function ($data){
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
        $table=DiyformModel::where('id',$data['diyform_id'])->value('table');
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

    public function getTypeLengths()
    {
        $lengthList = [
            'string'        => '255',
            'password'      => '100',
            'text'          => '0',
            'editor'        => '0',
            'number'        => '11',
            'date'          => '30',
            'time'          => '30',
            'datetime'      => '30',
            'datetimerange' => '255',
            'select'        => '5',
            'selects'       => '255',
            'image'         => '255',
            'images'        => '1500',
            'file'          => '255',
            'files'         => '1500',
            'switch'        => '5',
            'checkbox'      => '255',
            'radio'         => '5',
            'city'          => '255',
            'selectpage'    => '255',
            'selectpages'   => '255',
            'array'         => '0',
            'custom'        => '255',
        ];
        return $lengthList;
    }

    public function getAdminList($diyform_id)
    {
        $res= $this->where('status',1)
            ->where('diyform_id',$diyform_id)
            ->field('update_time,status,sort,create_time,id',true)
            ->select();
        $res=collection($res);
        if($res->isEmpty()){
            return [];
        }
        return $res->toArray();
    }
}
