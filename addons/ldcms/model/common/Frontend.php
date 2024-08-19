<?php


namespace addons\ldcms\model\common;


class Frontend extends Base
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->lang = ld_get_home_lang();
    }
    public function getLang()
    {
        return ld_get_home_lang();
    }
}