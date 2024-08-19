<?php

namespace app\admin\model\ldcms;

use addons\ldcms\model\common\Backend;
use Symfony\Component\VarExporter\VarExporter;
use think\Cache;
use think\Db;
use think\Exception;
use think\Lang;
use think\Model;


class Langs extends Backend
{
    // 表名
    protected $name = 'ldcms_langs';
    protected static $langpath = ADDON_PATH . 'ldcms' . DS . 'lang' . DS;
    protected static $siteinfopath = ADDON_PATH . 'ldcms' . DS . 'data' . DS . 'site_info' . DS;

    // 追加属性
    protected $append = [
        'create_time_text',
        'update_time_text'
    ];

    public static function init()
    {
        self::beforeWrite(function ($data) {
            /*设置默认语言,默认语言的状态必须为开启*/
            if($data['is_default']==1){
                Db::name('ldcms_langs')->where('id','<>',$data['id'])->update(['is_default'=>0]);
                $data['status']=1;
            }
            return $data;
        });

        self::afterWrite(function ($data){
            /*关闭默认语言后，将默认语言重置为中文*/
            if($data['is_default']==0){
                Db::name('ldcms_langs')->where('code','zh-cn')->update(['is_default'=>1]);
            }
            /*关闭语言后，将后台语言置空*/
            if($data['status']==0){
                ld_set_lang('backend_language','');
            }
        });

        self::afterInsert(function ($data) {
            self::createLangFile(self::$langpath . $data['code'] . '.php', self::$langpath . 'zh-cn.php');
            self::createLangSiteinfo('zh-cn', $data['code']);
        });

        self::beforeUpdate(function ($data) {
            $old_data = Db::name('ldcms_langs')->where('id', $data['id'])->find();
            if ($old_data['code'] != $data['code']) {
                rename(self::$langpath . $old_data['code'] . '.php', self::$langpath . $data['code'] . '.php');
                self::updateLangSiteinfo($old_data['code'], $data['code']);
            }
        });

        self::beforeDelete(function ($data) {
            if ($data['code'] == ld_get_lang() || $data['is_default']==1) {
                throw new Exception('当前语言被选中不能删除');
            }
        });

        self::afterDelete(function ($data) {
            @unlink(self::$langpath . $data['code'] . '.php');
            /*删除语言后重置后台语言为默认语言*/
            $default_lang= Db::name('ldcms_langs')->where('is_default', 1)->value('code');
            ld_set_lang('backend_language',$default_lang);
        });
        Cache::clear('ldcmslang');
    }

    /*创建语言文件*/
    protected static function createLangFile($carate_path, $copy_path)
    {
        $data = require $copy_path;
        // 写入lang文件
        $res = file_put_contents($carate_path, "<?php\n\n" . "return " . VarExporter::export($data) . ";\n", LOCK_EX);
        if (!$res) {
            throw new Exception('文件写入失败 请确定' . $carate_path . '目录有写入权限');
        }
    }

    protected static function createLangSiteinfo($copy_lang,$to_lang)
    {
        $siteinfoModel= SiteInfo::instance();
        $has=$siteinfoModel->where('lang',$to_lang)->count();
        if($has){
            return;
        }
        $copy_config=$siteinfoModel->where('lang',$copy_lang)->field('config')->find();
        $siteinfo=[
            'lang'=>$to_lang,
            'config'=>$copy_config['config']
        ];
        $siteinfoModel->save($siteinfo);
    }

    protected static function updateLangSiteinfo($old_lang,$new_lang)
    {
        SiteInfo::instance()->where('lang',$old_lang)->update(['lang'=>$new_lang]);
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

    protected function setCreateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setUpdateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function getDefaultLang()
    {
        return $this->where('is_default',1)->value('code');
    }
}