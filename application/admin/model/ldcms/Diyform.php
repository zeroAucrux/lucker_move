<?php

namespace app\admin\model\ldcms;


use addons\ldcms\model\common\Backend;
use addons\ldcms\utils\AutoSql;
use app\common\library\Menu;
use Exception;
use think\Db;

class Diyform extends Backend
{
    // 表名
    protected $name = 'ldcms_diyform';
    protected $sort = 'id DESC';
    public function getAdminList()
    {
        return $this->where('status',1)->order($this->sort)->column('name,title,table','id');
    }

    protected static function init() {
        /*新增前回调*/
        self::beforeInsert(function ($data) {
            if (!preg_match("/^([a-z0-9_]+)$/", $data['name'])) {
                throw new Exception("表单名称只支持小写字母、数字、下划线");
            }
            if (!preg_match("/^([a-z0-9_]+)$/", $data['table'])) {
                throw new Exception("表名只支持小写字母、数字、下划线");
            }
            $autoSql = AutoSql::instance();
            $data['table']="ldcms_".$data['table'];
            $table=$data['table'];
            $res=$autoSql->checkTable($table);
            if($res){
                throw new Exception($table.' 表已存在');
            }
            $fields="`diyform_id` int(10) NOT NULL ,`lang` varchar(255),`user_ip` varchar(255),`user_os` varchar(255),`user_bs` varchar(255),`create_time` bigint(20),`update_time` bigint(20),";
            $autoSql->createTable($table,$data['title'],'id',$fields);
        });

//        self::afterInsert(function ($data){
//            /*创建菜单*/
//            $menu=[
//                [
//                    'name'    => "ldcms/diyform_data/index/diyform_id/{$data['id']}",
//                    'title'   => $data['title'].'数据',
//                    'remark'  => $data['title'].'表单前台提交的数据',
//                    'icon'    => 'fa fa-list-alt',
//                    'ismenu'  => 1,
//                    'weigh'   => '22'
//                ]
//            ];
//            Menu::create($menu,'ldcms/diyform');
//        });

//        self::beforeUpdate(function ($data){
//            /*更新菜单*/
//            $row=self::find($data['id']);
//            if($row['title']!=$data['title']){
//                $menu=[
//                    [
//                        'name'=>'ldcms',
//                        'title'=>'LDCMS',
//                        'sublist'=>[
//                            [
//                                'name'=>'ldcms/diyform',
//                                'title'=>'自定义表单管理',
//                                'sublist'=>[
//                                    [
//                                        'name'    => "ldcms/diyform_data/index/diyform_id/{$data['id']}",
//                                        'title'   => $data['title'].'数据',
//                                        'remark'  => $data['title'].'表单前台提交的数据',
//                                    ]
//                                ]
//                            ]
//
//                        ]
//                    ]
//                ];
//                Menu::upgrade('ldcms',$menu);
//            }
//        });

        self::beforeDelete(function ($data){
            Db::name('ldcms_diyform_fields')->where('diyform_id',$data['id'])->delete();
//            Menu::delete("ldcms/diyform_data/index/diyform_id/{$data['id']}");
        });

        self::afterDelete(function ($data){
            $autoSql = AutoSql::instance();
            $table=$data['table'];
            $res=$autoSql->deleteTable($table);
        });
    }

    public function fields()
    {
        return $this->hasMany(DiyformFields::class,'diyform_id');
    }
}
