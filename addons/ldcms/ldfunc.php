<?php

/*字符串切割*/
error_reporting(E_ERROR | E_PARSE);

use Symfony\Component\VarExporter\VarExporter;
use think\Cache;

if (!function_exists('ld_filter')) {
    function ld_filter($string)
    {
        $config   = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($string);
    }
}

if (!function_exists('ld_str_cut')) {
    function ld_str_cut($str, $len, $charset = "utf-8")
    {
        return \addons\ldcms\utils\Common::substr_cut($str, $len, $charset);
    }
}

/*清除html标签与换行*/
if (!function_exists('ld_clear_html')) {
    function ld_clear_html($str)
    {
        $str = strip_tags($str);
        $str = str_replace("\r", "", $str); //过滤换行
        $str = str_replace("\n", "", $str); //过滤换行
        $str = str_replace("\t", "", $str); //过滤换行
        $str = str_replace("\r\n", "", $str); //过滤换行
        $str = str_replace('　', '', $str); // 清除大空格
        $str = str_replace('&nbsp;', '', $str); // 清除 &nbsp;
        $str = preg_replace('/\s+/', ' ', $str); // 清除空格
        return $str;
    }
}

/*格式化日期*/
if (!function_exists('ld_fdate')) {
    function ld_fdate($date, $format = 'Y-m-d H:i:s')
    {
        if (is_string($date)) {
            return date($format, strtotime($date));
        }
        if (is_int($date)) {
            return date($format, $date);
        }
    }
}

/*获取当前语言*/
if (!function_exists('ld_get_lang')) {
    function ld_get_lang($var = 'backend_language')
    {
        // 语言检测
        $lang = cookie($var);
        if (!$lang) {
            ld_set_lang('backend_language', config('ldcms.default_lang'));
        }
        return preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) ? $lang : config('ldcms.default_lang');
    }
}

/*设置当前语言*/
if (!function_exists('ld_set_lang')) {
    function ld_set_lang($var = 'backend_language', $language = 'zh-cn')
    {
        $lang = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $language) ? $language : config('ldcms.default_lang');
        // 语言检测
        cookie($var, $lang);
    }
}

/*获取前台当前语言*/
if (!function_exists('ld_get_home_lang')) {
    function ld_get_home_lang()
    {
        // 语言检测
        $lang = cookie('frontend_language');
        if (!$lang) {
            ld_set_home_lang(config('ldcms.default_lang'));
        }
        return preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) ? $lang : config('ldcms.default_lang');
    }
}
if (!function_exists('ld_set_home_lang')) {
    function ld_set_home_lang($language)
    {
        $lang    = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $language) ? $language : config('ldcms.default_lang');
        $oldlang = cookie('frontend_language');
        if ($oldlang && $lang != $oldlang) {  //如果切换了语言，更新栏目缓存
            Cache::tag('category')->clear();
        }
        // 语言检测
        cookie('frontend_language', $lang);
    }
}


/*获取当前语言的配置*/
if (!function_exists('ld_get_site_config')) {
    function ld_get_site_config($lang = 'zh-cn', $uncached = false)
    {
        $config    = [];
        $configArr = ld_get_site_fullconfig($lang, $uncached);
        if (empty($configArr)) {
            return [];
        }
        foreach ($configArr as $key => $value) {
            $config[$value['name']] = $value['value'];
        }
        return $config;
    }
}

function ld_set_site_fullconfig($array, $lang = 'zh-cn')
{
    \app\admin\model\ldcms\SiteInfo::instance()->setConfig($array, $lang);
    return true;
}

/**
 * 获取站点信息配置
 * @param $lang
 * @param false $uncached 是否实时获取
 * @return bool|mixed
 */
function ld_get_site_fullconfig($lang, $uncached = false)
{
    $siteInfoDb    = \app\admin\model\ldcms\SiteInfo::instance();
    $fullConfigArr = $siteInfoDb->getConfig($lang,$uncached);
    return $fullConfigArr;
}

/*获取文件目录*/
function ld_get_dirs($path)
{
    $list = array();
    if (!is_dir($path) || !$filename = scandir($path)) {
        return $list;
    }
    $files = count($filename);
    for ($i = 0; $i < $files; $i++) {
        $dir = $path . '/' . $filename[$i];
        if (is_dir($dir) && $filename[$i] != '.' && $filename[$i] != '..') {
            $list[$filename[$i]] = $filename[$i];
        }
    }
    return $list;
}

if (!function_exists('ld_get_child_cids')) {
    function ld_get_child_cids($cid, $withself = true)
    {
        return \addons\ldcms\model\Category::instance()->getChildrenIds($cid, $withself = true);
    }
}