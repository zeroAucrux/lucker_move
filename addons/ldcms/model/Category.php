<?php

namespace addons\ldcms\model;



use addons\ldcms\model\common\Frontend;
use addons\ldcms\model\Models as ModelsModel;
use app\admin\model\ldcms\Document as DocumentModel;
use think\Cache;
use think\Db;
use think\Debug;

class Category extends Frontend
{
    // 表名
    protected $name = 'ldcms_category';

    // 追加属性
    protected $append = [
        'url'
    ];

    public function getImageAttr($value, $data)
    {
        return $this->buildImage($value,$data);
    }

    public function getBigImageAttr($value,$data)
    {
        return $this->buildImage($value,$data);
    }

    public function getUrlAttr($value, $data)
    {
        return $this->buildUrl($value,$data);
    }

    public function buildImage($value,$data,$domain=true)
    {
        if (empty($value)) {
            return cdnurl(config('ldcms.category_noimage'),$domain);
        }
        return cdnurl($value, $domain);
    }

    public function buildUrl($value,$data,$domain=false,$rewrite=null)
    {
        $vars = [
            ':id' => $data['id'],
            ':category' => $data['urlname'],
        ];
        if (isset($data['type']) && isset($data['outlink']) && $data['type'] == '1') {
            /*站内链接*/
            if(strpos($data['outlink'],'@')===0){
                if(!$rewrite){
                    $config = get_addon_config('ldcms');
                    $rewrite = $config && isset($config['rewrite']) && $config['rewrite'] ? $config['rewrite'] : [];
                }
                $list_rewrite=$rewrite['lists/index']??'';
                $outlink=str_replace('@','',$data['outlink']);
                if(strpos($list_rewrite,'ldcms')!==false){
                    return '/ldcms'.$outlink;
                }else{
                    return $outlink;
                }
            }

            return $data['outlink'];
        }

        return addon_url('ldcms/lists/index', $vars, true,$domain);
    }

    /**
     * 获取当前id 的所有父ID
     * @param $id
     * @param false $withself
     * @return array
     */
    public function getParentIds($id)
    {
        $categorys = self::where('status', 1)->cache('categoryids')->column('id,pid,name,mid', 'id');
        if (!isset($categorys[$id])) {
            return [];
        }
        $function = function ($data, $id) use (&$function) {
            $arr = [];
            foreach ($data as $v) {
                if ($v['id'] == $id) {
                    $arr[] = $v['id'];
                    $arr = array_merge($function($data, $v['pid']), $arr);
                }
            }
            return $arr;
        };
        $returnData = $function($categorys, $id);
        return $returnData;
    }

    /**
     * 获取栏目的所有子节点ID
     * @param int  $id       栏目ID
     * @param bool $withself 是否包含自身
     * @return array
     */
    public function getChildrenIds($id, $withself = false)
    {
        $categorys = self::where('status', 1)->cache('categoryids')->column('id,pid,name,mid', 'id');
        if (!isset($categorys[$id])) {
            return [];
        }
        /*取到当前分类信息*/
        $current = $categorys[$id];
        $function = function ($data, $id) use (&$function, $current) {
            $arr = [];
            foreach ($data as $v) {
                /*剔除不是同一个模型的子类*/
                if ($v['pid'] == $id && $v['mid'] == $current['mid']) {
                    $arr[] = $v['id'];
                    $arr = array_merge($function($data, $v['id']), $arr);
                }
            }
            return $arr;
        };
        $returnData = $function($categorys, $id);
        if ($withself) {
            array_unshift($returnData, $id);
        }
        return $returnData;
    }


    /**
     * 获取首页栏目数据
     * @return array|mixed
     */
    public function getHomeCategoryData()
    {
        $lang = $this->getLang();
        $cache_key = 'category_arr_' . $lang;
        $categorys = Cache::get($cache_key);
//        $categorys=null;
        /*浏览权限*/
//        $auth=Auth::instance();
//        $user=$auth->getUser();
//        if(!empty($user)){
//            $authWhere['gid']=Db::raw('FIND_IN_SET('.$user->level.', `gid`) OR gid=""');
//        }else{
//            $authWhere['gid']="";
//        }
        if (!$categorys) {
            $res = Db::name('ldcms_category')
                ->where('status', 1)
                ->where('lang', $lang)
                ->field('update_time,status,sort', true)
                ->order($this->getSort())
                ->select();
            if (!$res) {
                return [];
            }

            $tree = $data = $top = [];
            /*获取配置*/
            $config = get_addon_config('ldcms');
            $rewrite = $config && isset($config['rewrite']) && $config['rewrite'] ? $config['rewrite'] : [];
            foreach ($res as $key => $value) {
                /*设置链接及图片*/
                $value['url'] = $this->buildUrl('',$value,null,$rewrite);
                $value['image'] = $this->buildImage($value['image'],$value);
                $value['big_image'] = $this->buildImage($value['big_image'],$value);

                if ($value['pid']) {
                    $tree[$value['pid']]['child'][] = $value; // 记录到关系树
                }
                else {
                    $top[] = $value; // 记录顶级菜单
                }

                $data[$value['id']] = $value;
            }

            /*统计子栏目数量*/
            foreach ($data as &$item){
                $item['child']=isset($tree[$item['id']]['child'])?count($tree[$item['id']]['child']):0;
            }
            $categorys['top'] = $top;
            $categorys['tree'] = $tree;
            $categorys['data'] = $data;
            Cache::tag('category')->set($cache_key, $categorys);
        }
        return $categorys;
    }

    /**
     * 首页导航
     * @param int $pid  父栏目
     * @return array|mixed
     */
    public function getHomeNav($pid = 0,$limit='')
    {
        $data = $this->getHomeCategoryData();
        if (empty($data)) {
            return [];
        }

        $tree = $top = [];
        foreach ($data['data'] as $value) {
            if (!$value['is_nav'])
                continue;
            if ($value['pid']) {
                $tree[$value['pid']]['child'][] = $value; // 记录到关系树
            }
            else {
                $top[] = $value; // 记录顶级菜单
            }
        }

        if ($pid) {
            $result = $tree[$pid]['child'] ?? [];
        }
        else {
            $result = $top ?? [];
        }

        if(!empty($limit)){
            $result=array_slice($result,0,$limit);
        }
        return $result;
    }

    /**
     * 获取分类 传入cid 则返回某个分类的。传入pid，则返回这个pid的所有子类
     *
     * @param [type] $cid
     * @param integer $pid
     * @param string $mtname
     * @return array
     */
    public function getHomeCategory($cid, $pid = 0,$limit='',$mtname='')
    {
        $data = $this->getHomeCategoryData();
        if (empty($data)) {
            return [];
        }
        if (!empty($cid)) {
            return $data['data'][$cid] ?? [];
        }

        if (!empty($pid)) {
            $result= $data['tree'][$pid]['child'] ?? [];
            /*调用limit*/
            if(!empty($result)&&!empty($limit)){
                $result=array_slice($result,0,$limit);
            }
            return $result;
        }
        return [];
    }

    /**
     * 根据urlname 获取分类信息
     * @param $urlname
     * @return array|mixed
     */
    public function getHomeCategoryByUrlname($urlname)
    {
        if (empty($urlname))
            return [];

        $data = $this->getHomeCategoryData();
        if (empty($data)) {
            return [];
        }
        foreach ($data['data'] as $item) {
            if ($item['urlname'] == $urlname) {
                return $item;
            }
        }
        return [];
    }

    /**
     * 不区分语言获取分类信息
     * @param $urlname
     */
    public function getLangByUrlname($urlname)
    {
        return $this->where('urlname',$urlname)->value('lang');
    }

    /**
     * 前端面包屑
     * @param $cid
     */
    public function getHomePosition($cid)
    {
        $cids=$this->getParentIds($cid);
        $data=$this->getHomeCategoryData();
        $result=[];
        foreach($cids as $cid){
            $result[]=$data['data'][$cid];
        }
        return $result;
    }
}
