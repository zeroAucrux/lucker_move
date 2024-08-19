<?php

namespace addons\ldcms\utils\fanyi\youdao\library;

class Translate
{

    public static function do_call($url, $method, $header, $param, $timeout = 3000)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        $data = http_build_query($param);
        if ($method == 'post') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else if ($method == 'get') {
            $url = $url . '?' . $data;
        } else {
            throw new \Exception('http method not support');
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        $r = curl_exec($curl);
        curl_close($curl);
        return $r;
    }

    public static function add_auth_params($param, $appKey, $appSecret)
    {
        if (array_key_exists('q', $param)) {
            $q = $param['q'];
        } else {
            $q = $param['img'];
        }
        $salt = self::create_uuid();
        $curtime = strtotime("now");
        $sign = self::calculate_sign($appKey, $appSecret, $q, $salt, $curtime);
        $param['appKey'] = $appKey;
        $param['salt'] = $salt;
        $param["curtime"] = $curtime;
        $param['signType'] = 'v3';
        $param['sign'] = $sign;
        return $param;
    }

    public static function create_uuid()
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $uuid;
    }

    public static function calculate_sign($appKey, $appSecret, $q, $salt, $curtime)
    {
        $strSrc = $appKey . self::get_input($q) . $salt . $curtime . $appSecret;
        return hash("sha256", $strSrc);
    }

    public static function get_input($q)
    {
        if (empty($q)) {
            return null;
        }
        $len = mb_strlen($q, 'utf-8');
        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }
}