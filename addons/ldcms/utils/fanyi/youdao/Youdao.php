<?php

namespace addons\ldcms\utils\fanyi\youdao;
use addons\ldcms\utils\fanyi\youdao\library\Translate;
use addons\ldcms\utils\fanyi\youdao\library\TranslateBatch;
use addons\ldcms\utils\fanyi\youdao\library\TranslateHtml;

class Youdao
{
    // 文本翻译
    public const API_URL = 'https://openapi.youdao.com/api';
    // 富文本翻译
    public const API_HTML_URL = 'https://openapi.youdao.com/translate_html';
    public const API_URL_BATCH = 'https://openapi.youdao.com/v2/api';
    public const CURL_TIMEOUT = 2000;

    /**
     * @var string 应用ID
     */
    private $appKey = '';

    /**
     * @var string 应用密钥
     */
    private $appSecret = '';

    /**
     * @param string $appKey
     * @param string $appSecret
     * @param bool $html
     * @throws \Exception
     */
    public function __construct(string $appKey = '', string $appSecret = '', bool $html = false)
    {
        if (empty($appKey)) {
            throw new \Exception('有道翻译 app key 不能为空!');
        }
        if (empty($appSecret)) {
            throw new \Exception('有道翻译 app secret 不能为空!');
        }
        $this->appKey    = $appKey;
        $this->appSecret = $appSecret;
    }

    public function getLangCode($code)
    {
        $addon_config=get_addon_config('ldcms');
        $youdao_lang_code=$addon_config['youdao_lang_code'];
        //根据value 找出code
        return array_search($code,$youdao_lang_code);
    }

    /**
     * @param $text
     * @param $from
     * @param $to
     * @return string
     * @throws \Exception
     */
    public function translate($text, $from, $to)
    {
        $params = [
            'q' => $text,
            'from' => $from,
            'to' => $to,
            //您的用户词表ID
            'vocabId' => ''
        ];
        $params = Translate::add_auth_params($params,$this->appKey , $this->appSecret);
        $ret = Translate::do_call(self::API_URL, 'post', [], $params);
        if(!$ret){
            throw new \Exception('curl请求失败，请检查curl是否正确安装');
        }
        $response=json_decode($ret, true);

        if ($response['errorCode']) {
            $errorMessage = "Error code: {$response['errorCode']}, 请查看: https://ai.youdao.com/DOCSIRMA/html/trans/api/wbfy/index.html#section-14";
            throw new \Exception($errorMessage);
        }
        return $response['translation'][0];
    }

    public function translateHtml($text,$from, $to)
    {
        $salt = TranslateHtml::create_guid();
        $args = array(
            'q' => $text,
            'appKey' => $this->appKey,
            'salt' => $salt,
        );
        $args['from'] = $from;
        $args['to'] = $to;
        $args['signType'] = 'v3';
        $curtime = strtotime("now");
        $args['curtime'] = $curtime;
        $signStr = $this->appKey . TranslateHtml::truncate($text) . $salt . $curtime . $this->appSecret;
        $args['sign'] = hash("sha256", $signStr);
        $ret = TranslateHtml::call(self::API_HTML_URL, $args);
        if(!$ret){
            throw new \Exception('curl请求失败，请检查curl是否正确安装');
        }
        $response=json_decode($ret, true);

        if ($response['errorCode']) {
            $errorMessage = "Error code: {$response['errorCode']}, 请查看: https://ai.youdao.com/DOCSIRMA/html/trans/api/wbfy/index.html#section-14";
            throw new \Exception($errorMessage);
        }
        return $response['data']['translation'];
    }

    /**
     * @param $data
     * @param $from
     * @param $to
     * @return array
     * @throws \Exception
     */
    public function translateBatch($data, $from, $to)
    {
        if (!$data || !is_array($data)) {
            return [];
        }

        $salt = TranslateBatch::create_guid();
        $args =[
            'q' => $data,
            'appKey' => $this->appKey,
            'salt' => $salt,
        ];
        $args['from'] =$from;
        $args['to'] = $to;
        $args['signType'] = 'v3';
        $curtime = strtotime("now");
        $args['curtime'] = $curtime;
        $signStr =  $this->appKey . TranslateBatch::truncate(implode("", $data)) . $salt . $curtime . $this->appSecret;
        $args['sign'] = hash("sha256", $signStr);
        $args['vocabId'] = '';
        $ret = TranslateBatch::call(self::API_URL_BATCH, $args);
        $response=json_decode($ret, true);

        if ($response['errorCode']) {
            $errorMessage = "Error code: {$response['errorCode']}, 请查看: https://ai.youdao.com/DOCSIRMA/html/trans/api/wbfy/index.html#section-14";
            throw new \Exception($errorMessage);
        }
        $results= $response['translateResults'];
        $returndata=[];$i=0;
        foreach ($data as $key=>$value){
            $returndata[$key]=$results[$i]['translation'];
            $i++;
        }
        return $returndata;
    }
}