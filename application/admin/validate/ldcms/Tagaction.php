<?php
// +----------------------------------------------------------------------
// | LDCMS  [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 http://www.zhuziweb.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: lande
// +----------------------------------------------------------------------

namespace app\admin\validate\ldcms;

use think\Validate;

class Tagaction extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'action|名称'=>'require|unique:ldcms_tagaction',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}