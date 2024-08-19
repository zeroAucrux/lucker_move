<?php

namespace app\admin\validate\ldcms;

use app\admin\validate\ldcms\traits\Extend;
use think\Validate;

class Links extends Validate
{
    use Extend;
    /**
     * 验证规则
     */
    protected $rule = [
        'title|标题'=>'require|uniqueLang:ldcms_links'
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'title.uniqueLang'=>'标题已存在'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}
