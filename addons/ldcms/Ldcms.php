<?php

namespace addons\ldcms;

use addons\ldcms\model\Langs;
use addons\ldcms\utils\Common;
use addons\ldcms\utils\LdRoute;
use addons\ldcms\utils\Menu as LdcmsMenu;
use app\common\library\Menu;
use think\Addons;
use think\Config;
use think\Db;
use think\Route;
/**
 * 插件
 */
class Ldcms extends Addons
{
    use LdRoute;
    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu= include ADDON_PATH . 'ldcms'.DS.'menu.php';
        Menu::create($menu);
        $dir=ADDON_PATH . 'ldcms'.DS;
        /*template*/
        $views=['default','en','ldcms2024','ldcms2024en'];
        foreach ($views as $view){
            $file=$dir.'view'.DS.'template_'.$view.'.zip';
            Common::unzip($dir.'view'.DS,$file);
        }

        /*设置网站主域名*/
        $config=get_addon_config('ldcms');
        if(empty($config['main_domain'])){
            $config['main_domain']=request()->host();
            set_addon_config('ldcms',$config);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("ldcms");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable("ldcms");
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("ldcms");
        return true;
    }

    /**
     * 插件升级方法
     * @return bool
     */
    public function upgrade()
    {
        //如果菜单有变更则升级菜单
//        Menu::upgrade('ldcms', $this->menu);
        $dir=ADDON_PATH . 'ldcms'.DS.'data'.DS;
        $file =  $dir. 'site_info.zip';
        @unlink($file);
        /*升级时删除模板压缩包*/
        $dir=ADDON_PATH . 'ldcms'.DS.'view'.DS;

        $views=['default','en','ldcms2024','ldcms2024en'];
        foreach ($views as $view){
            /*1.3.0 新模板不存在则进行解压缩*/
            if(!file_exists($dir.$view)){
                $file=$dir.'template_'.$view.'.zip';
                Common::unzip($dir,$file);
            }
            @unlink($dir. 'template_'.$view.'.zip');
        }

        /*更新菜单*/
        $this->updateMenu();
        /*升级时更新默认语言*/
        $langsModel=Db::name('ldcms_langs');
        if(!$langsModel->where('is_default',1)->count()){
            $langsModel->where('code','zh-cn')->update(['is_default'=>1]);
        }
        /*升级系统配置文件*/
        $this->updateConfig();
        return true;
    }

    /**
     * 应用初始化
     */
    public function appInit()
    {
        //后期废弃函数库
        include_once ADDON_PATH . 'ldcms' . DS . 'common.php';
        //替换上面的函数库，函数名增加了标识ld_
        include_once ADDON_PATH . 'ldcms' . DS . 'ldfunc.php';
        $config = get_addon_config('ldcms');
        $taglib = Config::get('template.taglib_pre_load');
        Config::set('template.taglib_pre_load', ($taglib ? $taglib . ',' : '') . 'addons\\ldcms\\taglib\\Ld');
        /*设置默认语言*/
        $config['default_lang']=Langs::instance()->getDefaultLang();
        $config['langs']=Langs::instance()->getList();
        $config['lang_list']=Langs::instance()->lists();
        Config::set('ldcms', $config);
        $this->routeLang($config);
    }

    public function configInit(&$param)
    {
        $param['ldcms'] = ['langs' => Config::get('ldcms.langs')];
    }

//    升级系统配置
    protected function updateConfig(){
        $full_config=get_addon_fullconfig('ldcms');
        $config=get_addon_config('ldcms');
        $config_key=array_keys($config);
        $update_config=include ADDON_PATH . 'ldcms' . DS . 'update_config.php';
        foreach ($update_config as $v){
            /*如果名称存在则不进行添加*/
            if(!in_array($v['name'],$config_key)){
                array_push($full_config,$v);
            }
        }
        /*自动设置主域名*/
        foreach ($full_config as &$v){
            if($v['name']=='main_domain'&&empty($v['value'])){
                $v['value']=request()->host();
            }
        }
        set_addon_fullconfig('ldcms',$full_config);
    }

    protected function updateMenu(){
        $upmenu=[
            [
                'name' => 'ldcms/slider',
                'title' => '轮播图管理',
                'remark' => '用于管理站点的Banner图、幻灯片等',
                'icon' => 'fa fa-photo',
                'weigh' => 75,
                'ismenu' => 1,
                'sublist' => [
                    ['name' => 'ldcms/slider/index', 'title' => '查看'],
                    ['name' => 'ldcms/slider/add', 'title' => '添加'],
                    ['name' => 'ldcms/slider/edit', 'title' => '编辑'],
                    ['name' => 'ldcms/slider/del', 'title' => '删除'],
                    ['name' => 'ldcms/slider/multi', 'title' => '批量更新'],
                ],
            ]
        ];
        LdcmsMenu::update('ldcms', 'ldcms/ad',$upmenu);

        $upmenu=[
            [
                'name' => 'ldcms/category',
                'title' => '栏目管理',
                'remark' => '管理网站的栏目与导航',
                'icon' => 'fa fa-file-text-o',
                'weigh' => 85,
                'ismenu' => 1,
                'sublist' => [
                    ['name' => 'ldcms/category/index', 'title' => '查看'],
                    ['name' => 'ldcms/category/add', 'title' => '添加'],
                    ['name' => 'ldcms/category/edit', 'title' => '编辑'],
                    ['name' => 'ldcms/category/del', 'title' => '删除'],
                    ['name' => 'ldcms/category/multi', 'title' => '批量更新'],
                    ['name' => 'ldcms/category/adds', 'title' => '批量添加'],
                ],
            ]
        ];
        LdcmsMenu::update('ldcms', 'ldcms/category',$upmenu);

        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/copy_langs');
        if(empty($ids)){
            $createMenu=[
                [
                    'name' => 'ldcms/copy_langs',
                    'title' => '跨语言复制数据',
                    'remark' => '',
                    'icon' => '',
                    'weigh' => 60,
                    'ismenu' => 0,
                    'sublist' => [
                        ['name' => 'ldcms/copy_langs/index', 'title' => '查看'],
                        ['name' => 'ldcms/copy_langs/add', 'title' => '添加'],
                        ['name' => 'ldcms/copy_langs/edit', 'title' => '编辑'],
                        ['name' => 'ldcms/copy_langs/del', 'title' => '删除'],
                        ['name' => 'ldcms/copy_langs/multi', 'title' => '批量更新'],
                        ['name' => 'ldcms/copy_langs/category', 'title' => '复制栏目'],
                        ['name' => 'ldcms/copy_langs/document', 'title' => '复制内容'],
                    ]
                ]
            ];
            LdcmsMenu::create($createMenu,'ldcms');
        }

        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/category_fields');
        if(empty($ids)){
            $createMenu=[
                [
                    'name' => 'ldcms/category_fields',
                    'title'=>'自定义栏目字段',
                    'remark' => '',
                    'icon' => '',
                    'weigh' => 60,
                    'ismenu' => 0,
                    'sublist' => [
                        ['name' => 'ldcms/category_fields/index', 'title' => '查看'],
                        ['name' => 'ldcms/category_fields/add', 'title' => '添加'],
                        ['name' => 'ldcms/category_fields/edit', 'title' => '编辑'],
                        ['name' => 'ldcms/category_fields/del', 'title' => '删除'],
                        ['name' => 'ldcms/category_fields/multi', 'title' => '批量更新'],
                    ]
                ]
            ];
            LdcmsMenu::create($createMenu,'ldcms');
        }

        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/themes');
        if(empty($ids)){
            $createMenu=[
                [
                    'name' => 'ldcms/themes',
                    'title' => '模板管理',
                    'remark' => '可上传安装（压缩包）、切换前台模板、下载模板备份、导入模板自带数据',
                    'icon' => 'fa fa-delicious',
                    'weigh' => 10,
                    'ismenu' => 1,
                    'sublist' => [
                        ['name' => 'ldcms/themes/index', 'title' => '查看'],
                        ['name' => 'ldcms/themes/local', 'title' => '上传安装'],
                        ['name' => 'ldcms/themes/install', 'title' => '直接安装'],
                        ['name' => 'ldcms/themes/enable', 'title' => '启用'],
                        ['name' => 'ldcms/themes/download', 'title' => '下载'],
                    ],
                ],
            ];
            LdcmsMenu::create($createMenu,'ldcms');
        }
        // 1.2.8
        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/category/translate');
        if(empty($ids)){
            LdcmsMenu::create([
                ['name'=>'ldcms/category/translate','title'=>'api翻译']
            ],'ldcms/category');
        }

        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/document/translate');
        if(empty($ids)){
            LdcmsMenu::create([
                ['name'=>'ldcms/document/translate','title'=>'api翻译']
            ],'ldcms/document');
        }
        // 1.3.0
        $ids = LdcmsMenu::getAuthRuleIdsByName('ldcms/tagaction');
        if(empty($ids)){
            $createMenu=[
                [
                    'name' => 'ldcms/tagaction',
                    'title' => '万能标签管理',
                    'remark' => '可以在此进行自定义模板标签<br> 教程：<a href="https://doc.fastadmin.net/ldcms/3421.html" target="_blank">https://doc.fastadmin.net/ldcms/3421.html</a>',
                    'icon' => 'fa fa-code',
                    'weigh' => 10,
                    'ismenu' => 1,
                    'sublist' => [
                        ['name' => 'ldcms/tagaction/index', 'title' => '查看'],
                        ['name' => 'ldcms/tagaction/add', 'title' => '添加'],
                        ['name' => 'ldcms/tagaction/edit', 'title' => '编辑'],
                        ['name' => 'ldcms/tagaction/del', 'title' => '删除'],
                        ['name' => 'ldcms/tagaction/multi', 'title' => '批量更新'],
                    ],
                ]
            ];
            LdcmsMenu::create($createMenu,'ldcms');
        }
    }


}