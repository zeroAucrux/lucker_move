<?php


namespace addons\ldcms\model\common;


class Backend extends Base
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->lang = ld_get_lang();
    }
}