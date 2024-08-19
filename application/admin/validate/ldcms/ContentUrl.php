<?php

namespace app\admin\validate\ldcms;

use app\admin\validate\ldcms\traits\Extend;
use think\Validate;

class ContentUrl extends Validate
{
    use Extend;
    /**
     * 验证规则
     */
    protected $rule = [
        'name|名称'=>'require|uniqueLang:ldcms_content_url',
        'url|链接'=>'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'name.uniqueLang'=>'名称已存在',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}
