<?php


namespace addons\ldcms\utils;


use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use think\Exception;

class Common
{
    /**
     * 获取当前站点
     * @return array|false|mixed|string
     */
    public static function getSite()
    {
        $site=request()->param('site','');
        $subdomain=self::subDomain();
        if(!empty($site)){
            return $site;
        }
        if(!empty($subdomain)){
            return $subdomain;
        }
        return false;
    }

    /**
     * 获取当前根域名
     * @access public
     * @return string
     */
    public static function rootDomain(): string
    {
        $item  = explode('.', request()->host());
        $count = count($item);
        $root  = $count > 1 ? $item[$count - 2] . '.' . $item[$count - 1] : $item[0];
        return $root;
    }

    /**
     * 获取当前子域名
     * @access public
     * @return string
     */
    public static function subDomain(): string
    {
        $rootDomain = self::rootDomain();
        if ($rootDomain) {
            $sub             = stristr(request()->host(), $rootDomain, true);
            $subDomain = $sub ? rtrim($sub, '.') : '';
        } else {
            $subDomain = '';
        }
        return $subDomain;
    }

    public static function substr_cut($str, $len, $charset = "utf-8")
    {
        $str = strip_tags($str);
        $str = str_replace("\r","",$str);//过滤换行
        $str = str_replace("\n","",$str);//过滤换行
        $str = str_replace("\t","",$str);//过滤换行
        $str = str_replace("\r\n","",$str);//过滤换行
        $str = trim($str);

        //如果截取长度小于等于0，则返回空
        if (is_numeric($len) && $len <= 0) {
            return "";
        }
        // 如果截取的长度为max,则为不进行截取
        if ($len == 'max') {
            return $str;
        }
        //如果截取长度大于总字符串长度，则直接返回当前字符串
        $sLen = strlen($str);
        if ($len >= $sLen) {
            return $str;
        }
        //判断使用什么编码，默认为utf-8
        if (strtolower($charset) == "utf-8") {
            $len_step = 3; //如果是utf-8编码，则中文字符长度为3
        } else {
            $len_step = 2; //如果是gb2312或big5编码，则中文字符长度为2
        }
        //执行截取操作
        $len_i = 0;
        //初始化计数当前已截取的字符串个数，此值为字符串的个数值（非字节数）
        $substr_len = 0; //初始化应该要截取的总字节数
        for ($i = 0; $i < $sLen; $i++) {
            if ($len_i >= $len) break; //总截取$len个字符串后，停止循环
            //判断，如果是中文字符串，则当前总字节数加上相应编码的中文字符长度
            if (ord(substr($str, $i, 1)) > 0xa0) {
                $i += $len_step - 1;
                $substr_len += $len_step;
            } else { //否则，为英文字符，加1个字节
                $substr_len++;
            }
            $len_i++;
        }

        //判断截取字符后加 “...”
        if ($substr_len < $sLen) {
            $result_str = substr($str, 0, $substr_len) . "...";
        } else {
            $result_str = substr($str, 0, $substr_len);
        }
        return $result_str;
    }

    // 获取用户浏览器类型
    public static function get_user_bs($bs = null)
    {
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $user_agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
        } else {
            return null;
        }

        // 直接检测传递的值
        if ($bs) {
            if (strpos($user_agent, strtolower($bs))) {
                return true;
            } else {
                return false;
            }
        }

        // 固定检测
        if (strpos($user_agent, 'micromessenger')) {
            $user_bs = 'Weixin';
        } elseif (strpos($user_agent, 'qq')) {
            $user_bs = 'QQ';
        } elseif (strpos($user_agent, 'weibo')) {
            $user_bs = 'Weibo';
        } elseif (strpos($user_agent, 'alipayclient')) {
            $user_bs = 'Alipay';
        } elseif (strpos($user_agent, 'trident/7.0')) {
            $user_bs = 'IE11'; // 新版本IE优先，避免360等浏览器的兼容模式检测错误
        } elseif (strpos($user_agent, 'trident/6.0')) {
            $user_bs = 'IE10';
        } elseif (strpos($user_agent, 'trident/5.0')) {
            $user_bs = 'IE9';
        } elseif (strpos($user_agent, 'trident/4.0')) {
            $user_bs = 'IE8';
        } elseif (strpos($user_agent, 'msie 7.0')) {
            $user_bs = 'IE7';
        } elseif (strpos($user_agent, 'msie 6.0')) {
            $user_bs = 'IE6';
        } elseif (strpos($user_agent, 'edge')) {
            $user_bs = 'Edge';
        } elseif (strpos($user_agent, 'firefox')) {
            $user_bs = 'Firefox';
        } elseif (strpos($user_agent, 'chrome') || strpos($user_agent, 'android')) {
            $user_bs = 'Chrome';
        } elseif (strpos($user_agent, 'safari')) {
            $user_bs = 'Safari';
        } elseif (strpos($user_agent, 'mj12bot')) {
            $user_bs = 'MJ12bot';
        } else {
            $user_bs = 'Other';
        }
        return $user_bs;
    }

// 获取用户操作系统类型
    public static function get_user_os($osstr = null)
    {
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $user_agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
        } else {
            return null;
        }

        // 直接检测传递的值
        if ($osstr) {
            if (strpos($user_agent, strtolower($osstr))) {
                return true;
            } else {
                return false;
            }
        }

        if (strpos($user_agent, 'windows nt 5.0')) {
            $user_os = 'Windows 2000';
        } elseif (strpos($user_agent, 'windows nt 9')) {
            $user_os = 'Windows 9X';
        } elseif (strpos($user_agent, 'windows nt 5.1')) {
            $user_os = 'Windows XP';
        } elseif (strpos($user_agent, 'windows nt 5.2')) {
            $user_os = 'Windows 2003';
        } elseif (strpos($user_agent, 'windows nt 6.0')) {
            $user_os = 'Windows Vista';
        } elseif (strpos($user_agent, 'windows nt 6.1')) {
            $user_os = 'Windows 7';
        } elseif (strpos($user_agent, 'windows nt 6.2')) {
            $user_os = 'Windows 8';
        } elseif (strpos($user_agent, 'windows nt 6.3')) {
            $user_os = 'Windows 8.1';
        } elseif (strpos($user_agent, 'windows nt 10')) {
            $user_os = 'Windows 10';
        } elseif (strpos($user_agent, 'windows phone')) {
            $user_os = 'Windows Phone';
        } elseif (strpos($user_agent, 'android')) {
            $user_os = 'Android';
        } elseif (strpos($user_agent, 'iphone')) {
            $user_os = 'iPhone';
        } elseif (strpos($user_agent, 'ipad')) {
            $user_os = 'iPad';
        } elseif (strpos($user_agent, 'mac')) {
            $user_os = 'Mac';
        } elseif (strpos($user_agent, 'sunos')) {
            $user_os = 'Sun OS';
        } elseif (strpos($user_agent, 'bsd')) {
            $user_os = 'BSD';
        } elseif (strpos($user_agent, 'ubuntu')) {
            $user_os = 'Ubuntu';
        } elseif (strpos($user_agent, 'linux')) {
            $user_os = 'Linux';
        } elseif (strpos($user_agent, 'unix')) {
            $user_os = 'Unix';
        } else {
            $user_os = 'Other';
        }
        return $user_os;
    }

    /** 解析"1:1\r\n2:3"格式字符串为数组
     * @param $value
     * @return array
     */
    public static function parseAttr($value){
        $data=[];
        //解析"1:1\r\n2:3"格式字符串为数组
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        foreach ($array as $val) {
            if(strpos($val,':')){
                list($k, $v) = explode(':', $val);
                $data[$k]   = $v;
            }
        }

        /*如果没有匹配到`:`分割符，就把解析后的字符串返回出去*/
        if(empty($data)){
            foreach ($array as $val){
                $data[$val]   = $val;
            }
        }

        return $data;
    }

    /**
     * 数组转换成换行字符串
     * @param $array
     * @return string
     */
    public static function parseEnter($array){
        if(is_array($array)){
            $string=implode("\r\n",$array);
            return $string;
        }else{
            return $array;
        }
    }

    /**
     * 解压文件
     * @return  string
     * @throws  Exception
     */
    public static function unzip($dir,$file)
    {
//        $dir=ADDON_PATH . 'ldcms'.DS.'data'.DS;
//        $file =  $dir. 'site_info.zip';
//        $file=ADDON_PATH . 'ldcms'.DS.$path;
        // 打开插件压缩包
        $zip = new ZipFile();
        try {
            $zip->openFile($file);
        } catch (ZipException $e) {
            $zip->close();
            throw new Exception('Unable to open the zip file');
        }
        // 解压插件压缩包
        try {
            $zip->extractTo($dir);
        } catch (ZipException $e) {
            throw new Exception('Unable to extract the file');
        } finally {
            @unlink($file);
            $zip->close();
        }
        return $dir;
    }
}