<?php

namespace app\admin\model\ldcms;

use addons\ldcms\model\common\Backend;
use addons\ldcms\utils\AutoSql;
use app\admin\model\AuthRule;
use app\common\library\Menu;
use Exception;
use think\Cache;
use think\Config;
use think\Db;
use tool\dir;


class Models extends Backend
{
    // 表名
    protected $name = 'ldcms_models';

    /*忽略状态的字段*/
    public $ignore_fields = [
        'id', 'mid', 'cid', 'title', 'admin_id', 'sort', 'lang', 'flag', 'tag', 'status','visits','likes','author','gid','show_time', 'create_time', 'update_time', 'delete_time'
    ];
    // 追加属性
    protected $sort = 'id DESC';
    protected $append = [
        'create_time_text',
        'update_time_text',
        'status_text'
    ];
    protected $type = [
        'default_fields' => 'array',
    ];

    protected static function init()
    {
        /*新增前回调*/
        self::beforeInsert(function ($data) {
            if (!preg_match("/^([a-z0-9_]+)$/", $data['table_name'])) {
                throw new Exception("表名只支持小写字母、数字、下划线");
            }
            $autoSql = AutoSql::instance();
            $table   = 'ldcms_document_' . $data['table_name'];
            $res     = $autoSql->checkTable($table);
            if ($res) {
                throw new Exception($table . ' 表已存在');
            }
            $autoSql->createTable($table, $data['name'], 'document_id', '', false);
        });

        self::afterInsert(function ($data) {
            /*创建菜单*/
            $menu = [
                [
                    'name'   => "ldcms/document/index/mid/{$data['id']}",
                    'title'  => $data['name'] . '内容',
                    'remark' => $data['name'] . '内容管理',
                    'icon'   => 'fa fa-list-alt',
                    'ismenu' => 1,
                    'weigh'  => $data['id']
                ]
            ];
            Menu::create($menu, 'ldcms/document');
        });

        self::beforeUpdate(function ($data) {
            /*更新菜单*/
            $row = self::find($data['id']);
            if ($row['name'] != $data['name']) {
                $oldMenu = AuthRule::getByName("ldcms/document/index/mid/{$data['id']}");
                $menu    = [
                    'name'   => "ldcms/document/index/mid/{$data['id']}",
                    'title'  => $data['name'] . '内容',
                    'remark' => $data['name'] . '内容管理',
                    'ismenu' => 1,
                ];
                AuthRule::update($menu, ['id' => $oldMenu['id']]);
            }
            if (!$data['status']) {
                $data['ismenu'] = 0;
            }
            /*更新状态*/
            if ($data['ismenu'] == 0) {
                Menu::disable("ldcms/document/index/mid/{$data['id']}");
            }

            if ($data['ismenu'] == 1) {
                $ids = Menu::getAuthRuleIdsByName("ldcms/document/index/mid/{$data['id']}");
                if (empty($ids)) {
                    /*创建菜单*/
                    $menu = [
                        [
                            'name'   => "ldcms/document/index/mid/{$data['id']}",
                            'title'  => $data['name'] . '内容',
                            'remark' => $data['name'] . '内容管理',
                            'icon'   => 'fa fa-list-alt',
                            'ismenu' => 1,
                            'weigh'  => $data['id']
                        ]
                    ];
                    Menu::create($menu, 'ldcms/document');
                } else {
                    Menu::enable("ldcms/document/index/mid/{$data['id']}");
                }
            }
            Cache::rm('__menu__');
        });

        self::beforeDelete(function ($data) {
            Db::name('ldcms_fields')->where('mid', $data['id'])->delete();
            Menu::delete("ldcms/document/index/mid/{$data['id']}");
            Cache::rm('__menu__');
        });
    }

    public function getStatusTextAttr($value, $data)
    {
        $status = [0 => '关闭', 1 => '启用'];
        return $status[$data['status']];
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

    public function getAdminList()
    {
        return $this->where('status', 1)->order($this->sort)->column('id,name,table_name,template_list,template_detail,default_fields', 'id');
    }

    /**
     * 获取模板
     * @param string $path
     * @return array|void
     * @Author: wusn <958342972@qq.com>
     * @DateTime: 11/27/20 11:51 AM
     */
    public function getTpl($type = 'list')
    {
        $config    = ld_get_site_config(ld_get_lang());
        $base_path = ADDON_PATH . 'ldcms' . DS . 'view' . DS . $config['template'] . DS;
        //列出文件夹下的所有文件
        $dir       = new \addons\ldcms\utils\Dir($base_path);
        $files     = $dir->toArray();
        $filenames = [];
        foreach ($files as $file) {
            if (strpos($file['filename'], $type . '_') !== false) {
                $filenames[] = [
                    'id'    => $file['filename'],
                    'title' => $file['filename']
                ];
            }
        }
        return $filenames;
    }

    /*获取内容主表的字段*/
    public function getDocumentFields($mid)
    {
        $modelData = $this->where('id', $mid)->find();
        $dbprefix  = Config::get('database.prefix');
        /*获取表中的字段*/
        $fieldList = $this->getTableFiles($dbprefix . 'ldcms_document');
        /*增加内容表的字段*/
        array_unshift($fieldList, ['name' => 'content', 'title' => '文章内容', 'type' => 'text']);
        $list = [];
        foreach ($fieldList as $field) {
            /*初始化字段数据*/
            $item = [
                'id'          => '',
                'mid'         => $mid,
                'field'       => $field['name'],
                'type'        => $field['type'],
                'title'       => $field['title'],
                'create_time' => 0,
                'update_time' => 0,
                'isfilter'    => 0,
                'islist'      => 0,
                'status'      => 1,
                'mid_text'    => $modelData['name'],
                'isdefault'   => 1
            ];

            $default_item = [];
            /*模型默认字段*/
            foreach ($modelData['default_fields'] as $docfield) {
                if ($docfield['field'] == $item['field']) {
                    $default_item = $docfield;
                    break;
                }
            }
            $list[] = array_merge($item, $default_item);
        }

        return $list;
    }

    /*获取显示的主表字段*/
    public function getShowDocumentFields($mid)
    {
        $document_fields = $this->getDocumentFields($mid);
        $keys            = [];
        foreach ($document_fields as $field) {
            if ($field['status'] == 1) {
                $keys[] = $field['field'];
            }
        }
        return $keys;
    }

    /**
     * 获取自定义颜色
     * @return array|false|string
     */
    public static function getCustomColor()
    {
        $colorArr = [
            'primary',
            'success',
            'warning',
            'danger',
            'info'
        ];
        $data = self::where('id', '>', 0)->column('id','id');
        $colors=[];
        foreach ($data as $index=> $item) {
            $colors[$item] = $colorArr[$index % 5];
        }
        return $colors;
    }
}