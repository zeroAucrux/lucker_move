<?php


namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;
use addons\ldcms\utils\AutoSql;
use addons\ldcms\utils\Common;
use Exception;
use think\exception\PDOException;
use think\Model;
use think\Url;

class Fields extends Frontend
{
    // 表名
    protected $name = 'ldcms_fields';

    protected $type=[
        'setting'=>'array'
    ];

    // 追加属性
    protected $append = [
        'content_list_arr'
    ];

    public function getContentListArrAttr($value,$data)
    {
        $value=$data['content_list'];
        if(empty($value)) return '';
        return Common::parseAttr($value);
    }

    /**
     * 获取前台多条件筛选
     * @param $mid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHomeFilter($mid,$title_text='全部')
    {
        $res= $this->where('status',1)
            ->where('mid',$mid)
            ->where('isfilter',1)
            ->order($this->sort)
            ->field('field,title,content_list')
            ->select();
        $res=collection($res);
        if($res->isEmpty()){
            return [];
        }
        $resArr=$res->toArray();

        $arr=[];
        /*解析url参数*/
        $info=parse_url(request()->url());

        $params=[];
        if(isset($info['query'])){
            parse_str($info['query'],$params);
        }
        /*生成where*/
        $where=[];
        foreach ( $resArr as $item){
            $item_content=$item['content_list_arr'];
            if(empty($item_content)) continue;
            $item_params=$params;
            unset($item_params[$item['field']]);
            $content=[
                [
                    'value'=>'',
                    'title'=>$title_text,
                    'active'=> request()->get($item['field']) === null ?1:0,
                    'url'=>empty($item_params)?$info['path']:'?'.http_build_query($item_params)
                ]
            ];
            foreach ($item_content as $k=>$i){
                $item_params[$item['field']]=$k;
                $active=(request()->get($item['field'])!==null&&request()->get($item['field'])==$k)?1:0;
                if($active){
                    $where[]="FIND_IN_SET('{$k}', `extend`.`{$item['field']}`)";
                }
                $content[]=[
                    'value'=>$k,
                    'title'=>$i,
                    'active'=>$active,
                    'url'=>'?'.http_build_query($item_params)
                ];
                $item['content']=$content;
            }
            $arr[]=$item;
        }
        return [
            'filterList'=>$arr,
            'filterWhere'=>implode(' AND ',$where)
        ];
    }
}