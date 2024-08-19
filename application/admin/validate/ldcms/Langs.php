<?php

namespace app\admin\validate\ldcms;

use think\Validate;

class Langs extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title|名称'=>'require|unique:ldcms_langs',
        'code|编码'=>'require|unique:ldcms_langs|langCode',
        'domain|绑定域名'=>'unique:ldcms_langs'
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'code.langCode'=>'编码只能是2~10位字母',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];

    protected function langCode($value, $rule, $data, $field){
        if(preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $value)){
            return true;
        }
        return false;
    }
}
