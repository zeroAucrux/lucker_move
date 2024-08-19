<?php


namespace addons\ldcms\controller;

use addons\ldcms\model\Category;
use addons\ldcms\model\Document;
use addons\ldcms\model\Langs;
use app\common\controller\Frontend;
use think\Config;
use think\Lang;

class Base extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $addon = 'ldcms';
    protected $categoryInfo = []; //栏目信息
    protected $addonConfig = []; //配置
    protected $contentInfo = []; //内容页
    protected $topid = 0; //顶级分类id  首页为0  可以判断栏目高亮
    protected $parentsId = []; //所有父分类id
    protected $childIds=[];//所有子分类id
    protected $lang = 'zh-cn';
    protected $siteConfig = [];
    protected $categoryModel = null;

    public function _initialize()
    {
        Config::set('template.view_path', ADDON_PATH . $this->addon . DS . 'view' . DS);
        parent::_initialize();

        //移除HTML标签
        $this->request->filter('trim,strip_tags,ld_filter,htmlspecialchars');
        $this->lang = ld_get_home_lang();
        $langs = config('ldcms.langs');

        /*调用栏目语言与当前语言不一致时，自动切换语言*/
        $urlname = $this->request->param('category');
        if (!empty($urlname)) {
            $this->categoryModel = Category::instance();
            $categoryLang = $this->categoryModel->getLangByUrlname($urlname);
            /*找不到当前url的lang*/
            if(!$categoryLang){
                abort(404, __('Not found'));
            }
            if ($categoryLang != ld_get_home_lang()) {
                ld_set_home_lang($categoryLang);
                $this->lang = $categoryLang;
            }
        }
        /*设置语言*/
        if ($this->request->param('lg')) {
            /*判断语言是否存在*/
            $this->validateLang($this->request->param('lg'), $langs);
            $language = request()->param('lg');
            ld_set_home_lang($language);
            $this->lang = $language;
        }

        /*加载语言包*/
        $this->loadlangall();

        $this->addonConfig = get_addon_config('ldcms');
        $this->siteConfig = ld_get_site_config($this->lang);

        $this->siteConfig['langs'] = $langs;
        $this->siteConfig['lg'] = $this->lang;
        $this->siteConfig['langsurl']=Langs::instance()->getListUrl();

        /*判断当前语言是否可以使用*/
        if(!in_array($this->lang,array_keys($langs))){
            $firstlang=array_shift($this->siteConfig['langsurl']);
            $this->error(__('Not found'),$firstlang['url'],'',3,['code'=>404]);
        }

        if (!$this->addonConfig['pc_site']) {
            /*网站暂停服务*/
            $this->error(__('Website maintenance'));
        }

        $template = Config::get('template.view_path') . $this->siteConfig['template'] . DS;

        if (!$template) {
            $this->error(__('Template does not exist'));
        }

        /*手机站点*/
        if ($this->addonConfig['wap_site'] && $this->request->isMobile()) {
            $template .= 'wap' . DS;
        }
        // 设定主题模板目录
        $this->view->engine->config('view_path', $template);
        $this->view->replace('__ADDON__', Config::get('site.cdnurl') . '/assets/addons/' . $this->addon);
        $this->getCategoryInfo();
        $this->siteConfig=array_merge($this->siteConfig,$this->apiSecret());
        $this->assign('ld', $this->siteConfig);
        $this->assign('topid', $this->topid);
    }

    /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlangall()
    {
        $lang = $this->lang;
        /*加载语言包*/
        $lang = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $this->lang) ? $this->lang : 'zh-cn';
        Lang::load(ADDON_PATH . $this->addon . '/lang/' . $lang . '.php');
    }

    /*获取分类信息*/
    protected function getCategoryInfo()
    {
        $urlname = $this->request->param('category');
        if (!empty($urlname)) {
            $categoryModel = $this->categoryModel;
            $this->categoryInfo = $categoryModel->getHomeCategoryByUrlname($urlname);
            if (!empty($this->categoryInfo['gid'])) {
                $this->checkAuth($this->categoryInfo['gid']);
            }
            if (empty($this->categoryInfo)) {
                /*内容不存在*/
                abort(404, __('Not found'));
            }
            /*父分类id*/
            $this->parentsId = $categoryModel->getParentIds($this->categoryInfo['id']);
            $this->childIds = $categoryModel->getChildrenIds($this->categoryInfo['id'],true);
            $this->topid = $this->parentsId[0];
            empty($this->categoryInfo['seo_title']) && $this->categoryInfo['seo_title'] = $this->categoryInfo['name'];
            $this->assign('category', $this->categoryInfo);
            $this->assign('parentsid', $this->parentsId);
            $this->assign('childids', $this->childIds);

            /*如果是单页直接获取内容*/
            if ($this->categoryInfo['mid'] == 1) {
                $this->contentInfo = Document::instance()->getHomeInfoByCid($this->categoryInfo['id']);
                if (empty($this->contentInfo)) {
                    /*内容不存在*/
                    abort(404, __("Not found"));
                }
                $custom_tpl=isset($this->contentInfo['custom_tpl'])&&!empty($this->contentInfo['custom_tpl'])?$this->contentInfo['custom_tpl']:false;
                $custom_tpl?$this->categoryInfo['template_detail']=$custom_tpl:'';
                $this->assign('content', $this->contentInfo);
            }
        }
    }
    /*验证浏览权限*/
    protected function checkAuth($gid)
    {
        $user = $this->auth->getUser();
        if (empty($user)) {
            /*浏览权限不足*/
            abort(403, __('No permission'));
        }
        if (!in_array($user['level'], explode(',', $gid))) {
            /*浏览权限不足*/
            abort(403, __('No permission'));
        }
    }

    /*验证语言是否存在*/
    protected function validateLang($lang, $langs)
    {
        /*判断语言是否存在*/
        if (!in_array($lang, array_keys($langs))) {
            $this->redirect(addon_url('ldcms/index/index'));
        }
    }

    /**
     * api授权
     * @return array
     */
    protected function apiSecret(){
        $timestamp=time();
        $signature=md5(md5($this->addonConfig['api_appid'].$this->addonConfig['api_secret'].$timestamp));
        return [
            'appid'=>$this->addonConfig['api_appid'],
            'timestamp'=>$timestamp,
            'signature'=>$signature,
        ];
    }
}