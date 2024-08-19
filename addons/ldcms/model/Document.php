<?php

namespace addons\ldcms\model;

use addons\ldcms\model\common\Frontend;
use think\Db;
use think\Exception;
use traits\model\SoftDelete;

class Document extends Frontend
{
    use SoftDelete;
    // 表名
    protected $name = 'ldcms_document';
    protected $sort = 'sort ASC,id DESC';

    protected $type = [
        'show_time' => 'timestamp',
    ];
    protected $append = [
        'url',
        'seo_description',
        'description',
        'curl'
    ];

    public function getStatus()
    {
        return [
            0 => '禁用',
            1 => '正常',
        ];
    }

    public function getStatusTextAttr($value, $data)
    {
        if ($data['show_time'] > time()) {
            return '定时发布';
        }
        $getStatus = $this->getStatus();
        return $getStatus[$data['status']];
    }

    public function getUrlAttr($value, $data)
    {
        if (empty($data['curlname'])) {
            return '';
        }

        $vars = [
            ':id' => $data['id'],
            ':category' => $data['curlname'],
        ];
        if (isset($data['outlink']) && !empty($data['outlink'])) {
            if(strpos($data['outlink'],'@')===0){
                $config = get_addon_config('ldcms');
                $rewrite = $config && isset($config['rewrite']) && $config['rewrite'] ? $config['rewrite'] : [];
                $list_rewrite=$rewrite['detail/index']??'';
                $outlink=str_replace('@','',$data['outlink']);
                if(strpos($list_rewrite,'ldcms')!==false){
                    return '/ldcms'.$outlink;
                }else{
                    return $outlink;
                }
            }

            return $this->getAttr('outlink');
        }
        /*如果是单页则返回栏目的 url*/
        if($data['mid']==1){
            return addon_url('ldcms/lists/index', $vars, true);
        }
        /*自定义url*/
        if(isset($data['custom_urlname'])&&!empty($data['custom_urlname'])){
            $vars[':id']=$data['custom_urlname'];
        }
        return addon_url('ldcms/detail/index', $vars, true);
    }

    /**
     * 获取栏目的url
     * @param [type] $value
     * @param [type] $data
     */
    public function getCurlAttr($value, $data)
    {
        if (empty($data['curlname'])) {
            return '';
        }
        $vars = [
            ':id' => $data['cid'],
            ':category' => $data['curlname'],
        ];
        return addon_url('ldcms/lists/index', $vars, true);
    }

    public function info($table_name, $id)
    {
        $extend_table_name = 'ldcms_document_' . $table_name;
        return $this->alias('document')
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->join('ldcms_document_content content', 'content.document_id=document.id', 'LEFT')
            ->join($extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT')
            ->field('document.*,extend.*,content.*,category.name cname,category.urlname curlname')
            ->find($id);
    }

    public function getImageAttr($value, $data)
    {
        if (empty($value)) {
            return cdnurl(config('ldcms.document_noimage'),true);
        }
        return cdnurl($value, true);
    }
    public function getDescriptionAttr($value, $data)
    {
        return $data['seo_description'];
    }

    public function scopeStatus($query)
    {
        $query->where('document.status', 1)->where('document.show_time', '<=', time());
    }

    /**
     * 前台列表
     * @param $params 数组
     * @return array
     * @throws Exception
     * @throws \think\exception\DbException
     */
    public function getHomeList($params)
    {
        $cid = (int) $params['cid'] ?? 0;
        $mid = (int) $params['mid'] ?? 0;
        $limit = $params['limit'] ?? '';
        $filterWhere = $params['filterWhere'];
        $_where = $params['_where'];
        $ext = empty($params['ext']) ? [] : explode(',', $params['ext']);
        $page = $params['page'];
        $simple = $params['simple'];
        //增加排序
        $order = isset($params['_order']) && !empty($params['_order']) ? $params['_order'] : $this->getSort();

        /*flag 标识 目前仅支持AND方式调用*/
        $customWhere = '';
        if (!empty($params['flag'])) {
            $flags = explode(',', $params['flag']);
            $customWhere .= $this->findInSet($flags, '`document`.`flag`');
        }
        if (!empty($params['tags'])) {
            $tags = explode(',', $params['tags']);
            $customWhere .= $this->findInSet($tags, '`document`.`tag`','OR');
        }
        if (!empty($params['condition'])) {
            $customWhere .= empty($customWhere) ? $params['condition'] : ' AND ' . $params['condition'];
        }
        $join = [
            ['ldcms_category category', 'category.id=document.cid', 'LEFT']
        ];
        $field = 'document.*,category.name cname,category.urlname curlname';

        /*获取扩展字段*/
        if (!empty($ext)) {
            /*内容表字段*/
            if (in_array('content', $ext)) {
                $join[] = ['ldcms_document_content content', 'content.document_id=document.id', 'LEFT'];
                $field .= ',content.content content';
                $key = array_search('content', $ext);
                unset($ext[$key]);
            }
        }
        if(!$mid){  // 未指定 模型的情况下则通过分类获取 mid
            $categoryModel = Category::instance();
            $category = $categoryModel->getHomeCategory($cid);
            $mid=$category['mid'];
            /*获取子类*/
            $cid = $categoryModel->getChildrenIds($cid, true);
        }

        if (!empty($category) || !empty($mid)) {
            $models = Models::instance()->getHomeList();
            /*附表字段*/
            if ((!empty($ext) || !empty($filterWhere)) && !empty($models[$mid])) {
                $table_name = $models[$mid]['table_name'];
                $extend_table_name = 'ldcms_document_' . $table_name;
                $join[] = [$extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT'];
                $str = '';
                foreach ($ext as $e) {
                    $str .= ',extend.' . $e . ' ' . $e;
                }
                $field .= $str;
            }
        }

        $listDb = $this::scope('status')->alias('document')
            ->join($join)
            ->field($field)
            ->where(function ($query) use ($cid,$mid) {
                if(!empty($mid)){
                    $query->where('document.mid',$mid);
                }
                if (!empty($cid)) {
                    $query->where('document.cid', 'in', $cid)->whereOr($this->findInSet($cid, '`document`.`sub_cid`'));
                }
            })
            ->where('document.lang', $this->getLang())
            ->where($customWhere)
            ->where($filterWhere)
            ->where($_where)
            ->order($order);
        //是否开启分页
        if ($page) {
            $result = $listDb->paginate($limit,$simple,[
                'type'=>'\addons\ldcms\utils\Bootstrap',
                'query' => request()->get()
            ]);
        } else {
            $result = $listDb->limit($limit)->select();
        }

        return $result;
    }

    /*前台搜索*/
    public function getHomeSearch($params)
    {
        $search=(string) request()->param('search',request()->param('title'));
        /*指定tag 多个用 "," 隔开*/
        $tags=request()->param('tags');
        /*指定栏目 多个栏目用 "," 隔开*/
        $cid=request()->param('cid');
        /*指定模型 */
        $mid=request()->param('mid');
        /*匹配模式 1 模糊匹配 0 精确匹配*/
        $fuzzy=request()->param('fuzzy',1);
        /*搜索字段 多个用｜隔开*/
        $field=request()->param('field','d.title');
        $_where=[];$auto_where=[];
        $select_field='d.*,c.name cname,c.urlname curlname';

        $join = [
            ['ldcms_category c', 'c.id=d.cid', 'LEFT']
        ];

        if(!empty($search) || $search == 0){
            if($fuzzy){
                $_where[$field]=['like','%'.$search.'%'];
            }else{
                $_where[$field]=['=',$search];
            }
        }

        /*自定义搜索字段*/
        $receive=request()->param(); $unfield=['title','cid','mid','fuzzy','field','search','tags','lang','id','searchtpl','status','page','s','__searchtoken__','__token__','addon','controller','action','module','lg'];
        foreach ($receive as $key => $value) {
            if (!in_array($key,$unfield)) {
                if (preg_match('/^[\w\-\.]+$/', $key)) { // 带有违规字符时不带入查询
                    $auto_where[$key] = $value;
                }
            }
        }

        if(!empty($cid)){
            $cidarr=explode(',',$cid);
            $cids=[];
            foreach ($cidarr as $value){
                $cids=array_merge($cids,Category::instance()->getChildrenIds($value,true));
            }
            $_where['cid']=['in',implode(',',$cids)];
        }

        if(!empty($tags)){
            $tags=explode(',',$tags);
            $_where[]=['exp',Db::raw($this->findInSet($tags,'`d`.`tag`','OR'))];
        }
        /*指定模型时可以查询副表字段*/
        if(!empty($mid)){
            $_where['d.mid']=$mid;
            $models = Models::instance()->getHomeList();
            $table_name = $models[$mid]['table_name'];
            $extend_table_name = 'ldcms_document_' . $table_name;
            $join[] = [$extend_table_name . ' e', 'e.document_id=d.id', 'LEFT'];
            $select_field.=',e.*';
        }

        $pageQuery=[
            'search'=>request()->request('search'),
        ];
        $limit = $params['limit'] ?? '';

        $result= $this->alias('d')
            ->where('d.status',1)
            ->join($join)
            ->field($select_field)
            ->where($_where)
            ->where($auto_where)
            ->where('d.lang', $this->getLang())
            ->order($this->getSort())
            ->paginate([
                'list_rows' => $limit,
                'query' => $pageQuery
            ]);
        if($result->isEmpty()){
            abort('404', __('Not found'));
        }
        return $result;
    }

    /*获取文章内容*/
    public function getHomeInfoById($id)
    {
        $models = Models::instance()->getHomeList();
        $info = $this->alias('document')
            ->status()
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->where('document.lang', $this->getLang())
            ->where('document.id|document.custom_urlname', $id)
            ->field('document.*,category.name cname,category.urlname curlname')
            ->find();

        if (empty($info)) {
            return [];
        }
        $info->setInc('visits');
        $table_name = $models[$info['mid']]['table_name'];
        $extend_table_name = 'ldcms_document_' . $table_name;
        $res = Db::name('ldcms_document_content')->alias('content')
            ->join($extend_table_name . ' extend', 'extend.document_id=content.document_id', 'LEFT')
            ->where('content.document_id', $info['id'])
            ->find();

        $info = array_merge($info->toArray(), $res);
        $info['content'] = $this->pregContentUrl($info['content']);
        return $info;
    }

    /**
     * 根据栏目id获取排序最高的一条内容，用于栏目单页
     * @param $cid
     */
    public function getHomeInfoByCid($cid)
    {
        $models = Models::instance()->getHomeList();
        $category = Category::instance()->where('id', $cid)->find();
        if ($category['type'] == 1) { //跳转链接
            return [];
        }
        $info = $this->alias('document')
            ->status()
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->where('document.cid', $cid)
            ->where('document.lang', $this->getLang())
            ->order($this->sort)
            ->field('document.*,category.name cname,category.urlname curlname,category.type ctype')
            ->find();
        if (empty($info)) {
            return [];
        }

        $info->setInc('visits');

        $table_name = $models[$info['mid']]['table_name'];
        $extend_table_name = 'ldcms_document_' . $table_name;

        $res = Db::name('ldcms_document_content')->alias('content')
            ->join($extend_table_name . ' extend', 'extend.document_id=content.document_id', 'LEFT')
            ->where('content.document_id', $info['id'])
            ->find();
        $info = array_merge($info->toArray(), $res);
        return $info;
    }

    /**
     * 上一页 下一页
     * @param $id
     * @param $cid
     * @param $type
     * @param $order
     * @return false
     */
    public function getPrevNext($id, $cid, $type,$order='')
    {
        //增加排序
        $order = $order ? : $this->getSort();

        $ids = $this->alias('document')
            ->status()
            ->where('cid', $cid)
            ->where('document.lang', $this->getLang())
            ->order($order)->column('id');
        $ids = array_merge($ids);
        //返回值对应的key
        $key = array_search($id, $ids);
        $this->alias('document')->status();
        if ($type == "next") {
            if (!isset($ids[$key + 1])) {
                return false;
            }
            return $this->getHomeInfoById($ids[$key + 1]);
        } else {
            if (!isset($ids[$key - 1])) {
                return false;
            }
            return $this->getHomeInfoById($ids[$key - 1]);
        }
    }

    /*替换内链*/
    public function pregContentUrl($content)
    {
        $tags = ContentUrl::instance()->where('lang', $this->getLang())->field('name,url')->select();
        if (empty($tags)) {
            return $content;
        }

        // 将A链接保护起来,alt、title保护起来
        $rega = "/(<a .*?>.*?<\/a>)|(alt=.*?>)|(title=.*?>)/i";
        preg_match_all($rega, $content, $matches1);
        foreach ($matches1[0] as $key => $value) {
            $content = str_replace($value, '#rega:' . $key . '#', $content);
        }

        // 去除包含或重复的短tags,实现长关键字优先
        foreach ($tags as $key => $value) {
            foreach ($tags as $key2 => $value2) {
                if (strpos($value2['name'], $value['name']) !== false && $key != $key2) {
                    unset($tags[$key]);
                }
            }
        }
        $config = get_addon_config('ldcms');
        // 执行内链替换
        foreach ($tags as $value) {
            $content = preg_replace('/' . $value['name'] . '/', '<a href="' . $value['url'] . '">' . $value['name'] . '</a>', $content, $config['content_url_num'] ?: 3);
        }

        // 还原保护的内容
        $pattern = '/\#rega:([0-9]+)\#/';
        if (preg_match_all($pattern, $content, $matches2)) {
            $count = count($matches2[0]);
            for ($i = 0; $i < $count; $i++) {
                $content = str_replace($matches2[0][$i], $matches1[0][$matches2[1][$i]], $content);
            }
        }

        return $content;
    }

    /*统计文档数量*/
    public function getListRows($cid)
    {
        $categoryModel = Category::instance();
        /*获取子类*/
        $cid = $categoryModel->getChildrenIds($cid, true);
        return $this->where('lang', $this->getLang())
            ->where('cid', 'in', $cid)->count();
    }
}