<?php


namespace app\admin\model\ldcms;


use addons\ldcms\model\common\Backend;
use fast\Pinyin;

class Tags extends Backend
{
    protected $name='ldcms_tags';
    protected $sort='use_num DESC';

    protected $append = [
        'url'
    ];

    public function getUrlAttr($value, $data)
    {
        if (empty($data['name'])) {
            return '';
        }
        $vars = [
            ':tag' => $data['name'],
        ];

        return addon_url('ldcms/tag/index', $vars, true);
    }

    /**
     * 插入tags 仅有$tags时判断为新增，仅有$old_tags时判断为删除，两者都存在时判断为修改
     * @param $tags
     * @param string $old_tags
     * @throws \think\Exception
     */
    public function insertTag($tags='',$old_tags='')
    {
        /*新增的时候加1*/
        /*修改的时候需要对比之前的数据，如果没有改变，则不更新，如果有新增,则加1，如果删除，则减1*/
        /*删除的时候减1*/

        $incData=[];
        $addData=[];
        $decData=[];
        /*新增*/
        if(!empty($tags)&&empty($old_tags)){
            $tags=explode(',',$tags);
            $res=$this->hasTagList($tags);
            $incData=$res['inc_data'];
            $addData=$res['add_data'];
        }

        /*修改*/
        if(!empty($tags)&&!empty($old_tags)){
            /*
             * 先取 $tags $old_tags的合集
             * 取合集与$old_tags的差集,代表是新增的tag
             * 取合集与$tags的差集,代表是删除的tag
            */
//            $tags='a';
//            $old_tags='a,c';
            $tags=explode(',',$tags);
            $old_tags=explode(',',$old_tags);
            $total_tags=array_unique(array_merge($tags,$old_tags));
            $need_add=array_diff($total_tags,$old_tags);
            $need_del=array_diff($total_tags,$tags);
            $res=$this->hasTagList($need_add);
            $incData=$res['inc_data'];
            $addData=$res['add_data'];
            $decData=$need_del;
        }

        /*删除*/
        if(empty($tags)&&!empty($old_tags)){
            $decData=explode(',',$old_tags);
        }

        if(!empty($incData)){
            $this->where('title','in',$incData)->setInc('use_num');
        }
        if(!empty($addData)){
            $this->saveAll($addData);
        }
        if(!empty($decData)){
            $this->where('title','in',$decData)->setDec('use_num');
        }
    }

    /**
     * @param $tags
     * @return array[]
     */
    private function hasTagList($tags){
        $incData=[];$addData=[];
        $taglist=$this->column('title');
        foreach ($tags as $tag){
            if(in_array($tag,$taglist)){
                $incData[]=$tag;
            }else{
                $addData[]=[
                    'title'=>$tag,
                    'name'=>Pinyin::get($tag),
                    'use_num'=>1,
                    'lang'=>$this->lang
                ];
            }
        }
        return ['inc_data'=>$incData,'add_data'=>$addData];
    }
}