<?php

/*字符串切割*/
error_reporting(E_ERROR | E_PARSE);

use Symfony\Component\VarExporter\VarExporter;
use think\Config;

if (!function_exists('p')) {
    /**
     * 打印
     * Author:939134342@qq.com
     * DateTime:2022/6/21 9:56 下午
     * @param $vars
     */
    function p($vars)
    {
        if (is_bool($vars) || empty($vars)) {
            echo "<pre>";
            var_dump($vars);
            echo "</pre>";
        } else {
            echo "<pre>";
            print_r($vars);
            echo "</pre>";
        }
    }
}
if (!function_exists('str_cut')) {
    function str_cut($str, $len, $charset = "utf-8")
    {
        return \addons\ldcms\utils\Common::substr_cut($str, $len, $charset);
    }
}

/*清除html标签与换行*/
if (!function_exists('clear_html')) {
    function clear_html($str)
    {
        $str = strip_tags($str);
        $str = str_replace("\r", "", $str); //过滤换行
        $str = str_replace("\n", "", $str); //过滤换行
        $str = str_replace("\t", "", $str); //过滤换行
        $str = str_replace("\r\n", "", $str); //过滤换行
        $str = trim($str);
        return $str;
    }
}

/*格式化日期*/
if (!function_exists('fdate')) {
    function fdate($date, $format = 'Y-m-d H:i:s')
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
if (!function_exists('get_lang')) {
    function get_lang($var = 'backend_language')
    {
        // 语言检测
        $lang = cookie($var);
        if(!$lang){
            set_lang('backend_language',config('ldcms.default_lang'));
        }
        return preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) ? $lang : config('ldcms.default_lang');
    }
}

/*设置当前语言*/
if (!function_exists('set_lang')) {
    function set_lang($var = 'backend_language', $language = 'zh-cn')
    {
        $lang = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $language) ? $language :  config('ldcms.default_lang');
        // 语言检测
        cookie($var, $lang);
    }
}

/*获取前台当前语言*/
if (!function_exists('get_home_lang')) {
    function get_home_lang()
    {
        // 语言检测
        $lang = cookie('frontend_language');
        if(!$lang){
            set_home_lang(config('ldcms.default_lang'));
        }
        return preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) ? $lang :  config('ldcms.default_lang');
    }
}
if (!function_exists('set_home_lang')) {
    function set_home_lang($language)
    {
        $lang = preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $language) ? $language :  config('ldcms.default_lang');
        // 语言检测
        cookie('frontend_language', $lang);
    }
}


/*获取当前语言的配置*/
if (!function_exists('get_site_config')) {
    function get_site_config($lang = 'zh-cn')
    {
        $config = [];
        $configArr = get_site_fullconfig($lang);
        if (empty($configArr)) {
            return [];
        }
        foreach ($configArr as $key => $value) {
            $config[$value['name']] = $value['value'];
        }
        return $config;
    }
}

function set_site_fullconfig($array, $lang = 'zh-cn')
{
    $file = ADDON_PATH . 'ldcms' . DS . 'data' . DS . 'site_info' . DS . $lang . '.php';
    $ret = file_put_contents($file, "<?php\n\n" . "return " . VarExporter::export($array) . ";\n", LOCK_EX);
    if (!$ret) {
        throw new Exception("配置写入失败");
    }
    return true;
}

function get_site_fullconfig($lang)
{
    $configFile = ADDON_PATH . 'ldcms' . DS . 'data' . DS . 'site_info' . DS . $lang . '.php';
    $fullConfigArr = [];
    if (is_file($configFile)) {
        $fullConfigArr = include $configFile;
    }
    return $fullConfigArr;
}
/*获取文件目录*/
function get_dirs($path)
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