<?php


namespace addons\ldcms\model;


use addons\ldcms\model\common\Frontend;
use fast\Pinyin;
use think\Db;

class Tags extends Frontend
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

    /*前台调用列表*/
    public function getHomeList($cid=0,$document_id=0,$limit=16)
    {
        $where=[];
        /*调取内容详情的tags*/
        if(!empty($document_id)){
            $tag=Document::instance()->where('id',$document_id)->value('tag');
            $tag=explode(',',$tag);
        }
        /*调取栏目下的所有tags*/
        if(!empty($cid)){
            $cidarr=explode(',',$cid);
            $cids=[];
            foreach ($cidarr as $value){
                $cids=array_merge($cids,Category::instance()->getChildrenIds($value,true));
            }
            $tagarr=Db::name('ldcms_document')
                ->where('cid','in',$cids)
                ->where('tag','<>','')
                ->field('tag')->group('tag')->select();
            $tag=[];
            foreach ($tagarr as $item){
                $tag=array_merge($tag,explode(',',$item['tag']));
            }
        }
        //如果没有tag 并且传入了 文档ID 或者栏目ID,那么就返回空数据
        if(empty($tag)&&(!empty($document_id)||!empty($cid))){
            return ['data'=>[],'render'=>'','total'=>0];
        }
        /*没有tag 默认调用所有*/
        if(!empty($tag)){
            $where['title']=['in',$tag];
        }

        $result=$this->where($where)->order($this->getSort())->paginate($limit);
        return ['data' => $result->toArray()['data'], 'render' => $result->render(),'total'=>$result->total()];
    }
}