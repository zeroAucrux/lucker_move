<?php


namespace app\admin\model\ldcms;


use addons\ldcms\model\common\Backend;

class SiteInfo extends Backend
{
    protected $name='ldcms_siteinfo';

    protected $type=['config'=>'array'];

    /**
     * 保存配置
     * @param $data
     * @param $lang
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setConfig($data,$lang){
        $res=$this->where('lang',$lang)->find();
        if($res){
            $res->save(['config'=>$data]);
        }else{
            $this->save(['lang'=>$lang,'config'=>$data]);
        }

        cache('site_info',null);
    }

    /**
     * @param $lang  语言
     * @param $uncached  实时获取
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getConfig($lang,$uncached=false)
    {
        if($uncached){
            $res=$this->where('lang',$lang)->find();
        }else{
            $res=$this->where('lang',$lang)->cache('site_info_'.$lang,3600)->find();
        }

        return $res['config'];
    }
}