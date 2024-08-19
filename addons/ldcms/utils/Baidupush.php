<?php


namespace addons\ldcms\utils;


use fast\Http;

class Baidupush
{
    protected static $instance = null;
    protected $error=null;
    protected $data=[];

    public static function instance($data = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($data);
        }

        return self::$instance;
    }

    /*普通推送*/
    public function normal($urls)
    {
        $site=request()->domain();
        $config=get_addon_config('ldcms');
        if(!isset($config['baidupushzz_token'])||empty($config['baidupushzz_token'])){
            $this->error='请先配置百度普通推送的token';
            return false;
        }
        $token=$config['baidupushzz_token'];
        $url = "http://data.zz.baidu.com/urls?site={$site}&token={$token}";
        return $this->request($url,$urls);
    }

    /*快速推送*/
    public function daily($urls)
    {
        $site=request()->domain();
        $config=get_addon_config('ldcms');
        if(!isset($config['baidupushks_token'])||empty($config['baidupushks_token'])){
            $this->error='请先配置百度快速推送的token';
            return false;
        }
        $token=$config['baidupushzz_token'];
        $url = "http://data.zz.baidu.com/urls?site={$site}&token={$token}&type=daily";
        return $this->request($url,$urls);
    }

    /**
     * @param $url 请求链接
     * @param $urls 需要推送的链接
     * @return false
     */
    public function request($url,$urls)
    {
        try {
            $options = [
                CURLOPT_HTTPHEADER => [
                    'Content-Type: text/plain'
                ]
            ];

            $ret = Http::sendRequest($url, implode("\n", $urls), 'POST', $options);
            if ($ret['ret']) {
                $json = (array)json_decode($ret['msg'], true);
                if (!$json || isset($json['error'])) {
                    $this->error=$json['message'];
                    return false;
                }
                $this->setData($json);
                return true;
            }
        } catch (\Exception $e) {
            $this->error=$e->getMessage();
        }
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置返回的数据
     * @param mixed $data
     */
    protected function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * 获取返回的数据
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}