<?php


namespace addons\ldcms\controller\api;


use app\common\controller\Api;
use think\Config;
use think\Lang;

class Base extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    protected $addon = 'ldcms';
    protected $addonConfig = []; //配置
    protected $siteConfig = []; //站点配置

    public function _initialize()
    {

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Expose-Headers: __token__'); //跨域让客户端获取到
        }
        //跨域检测
        check_cors_request();

        parent::_initialize();

        $this->request->filter('trim,strip_tags,ld_filter,htmlspecialchars');
        Config::set('default_return_type', 'json');
        $this->addonConfig = get_addon_config($this->addon);
        $this->siteConfig = ld_get_site_config();
        if (!$this->addonConfig['api_site']) {
            $this->error('网站API维护中,暂时无法访问~');
        }

        if ($this->addonConfig['api_auth']) {
            $this->checkAccess();
        }

        //这里手动载入语言包
        Lang::load(ROOT_PATH . '/addons/ldcms/lang/zh-cn.php');
    }

    /**
     * 认证api
     */
    public function checkAccess()
    {
        $config = $this->addonConfig;
        // 判断用户
        if (!$config['api_appid']) {
            $this->error('请求失败：管理后台接口认证用户配置有误', '', 403);
        }
        // 判断密钥
        if (!$config['api_secret']) {
            $this->error('请求失败：管理后台接口认证密钥配置有误', '', 403);
        }
        $appid=$this->request->param('appid')?$this->request->param('appid'):$this->request->header('appid');
        $timestamp=$this->request->param('timestamp')?$this->request->param('timestamp'):$this->request->header('timestamp');
        $signature=$this->request->param('signature')?$this->request->param('signature'):$this->request->header('signature');
        // 获取参数
        if (!$appid) {
            $this->error('请求失败：未检查到appid参数', '', 403);
        }
        if (!$timestamp) {
            $this->error('请求失败：未检查到timestamp参数', '', 403);
        }
        if (!$signature) {
            $this->error('请求失败：未检查到signature参数', '', 403);
        }

        $host=request()->scheme()."://".request()->host(true);
        // 验证时间戳
        if (strpos($_SERVER['HTTP_REFERER'],$host) === false && time() - $timestamp > 15) { // 请求时间戳认证，不得超过15秒
            $this->error('请求失败：接口时间戳验证失败！', '', 403);
        }

        // 验证签名
        if ($signature != md5(md5($config['api_appid'] . $config['api_secret'] . $timestamp))) {
            $this->error('请求失败：接口签名信息错误！', '', 403);
        }
    }
}