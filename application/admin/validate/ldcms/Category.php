<?php

namespace app\admin\validate\ldcms;

use app\admin\validate\ldcms\traits\Extend;
use think\Validate;

class Category extends Validate
{
    use Extend;
    /**
     * 验证规则
     */
    protected $rule = [
        'name|名称'=>'require',
        'urlname|URL名称'=>'require|uniqueLang:ldcms_category',
        'template_detail|详情页模板'=>'requireIf:type,0'
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'urlname.uniqueLang'=>'URL名称已存在'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}
