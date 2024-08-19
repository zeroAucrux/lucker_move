<?php


namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;

class Langs extends Frontend
{
    protected $name='ldcms_langs';
    public function lists()
    {
        return $this->cache(true,null,'ldcmslang')->where('status',1)->column('code,title,sub_title,status,is_default,domain','code');
    }

    public function getList()
    {
        $lists=$this->lists();
        return array_column($lists,'title','code');
    }

    public function getLangKey()
    {
        return array_keys($this->lists());
    }

    /*默认语言*/
    public function getDefaultLang()
    {
        $lists=$this->lists();
        foreach ($lists as $item){
            if($item['is_default']==1){
                return $item['code'];
            }
        }
    }

    /*获取语言列表并生成url*/
    public function getListUrl()
    {
        $mode=config('ldcms.rewrite_mode');
        $lists=$this->lists();
        $main_domain=config('ldcms.main_domain');
        $lang=ld_get_home_lang();
        foreach($lists as $k=>&$item){
            switch ($mode){
                case '0': //变量模式
                    $item['url']=request()->scheme().'://'.$main_domain.addon_url('ldcms/index/index',['lg'=>$item['code']],true,'');
                    break;
                case '1': //目录模式
                    $url=addon_url('ldcms/index/index',[],true,'');
                    if(strpos($url, '/' . $lang.'/') === 0 || strpos($url,'/ldcms/'  . $lang.'/') === 0){
                        $url=str_replace('/' . $lang,'',$url);
                    }
                    /*如果是默认语言 则不加目录*/
                    if($item['code']==config('ldcms.default_lang')){
                        $item['url']=request()->scheme().'://'.$main_domain.$url;
                    }else{
                        $item['url']=request()->scheme().'://'.$main_domain.$url.$item['code'];
                    }
                    break;
                case '2': //子域名模式
                    $item['url']=request()->scheme().'://'.$item['domain'].addon_url('ldcms/index/index',[],true,'');
                    break;
                default:
                    $item['url']=request()->scheme().'://'.$main_domain.addon_url('ldcms/index/index',['lg'=>$item['code']],true,'');
            }
        }
        return $lists;
    }
}