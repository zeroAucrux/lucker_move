<?php

namespace app\admin\model\ldcms;

use addons\ldcms\model\common\Backend;
use app\admin\model\ldcms\Document as DocumentModel;
use app\admin\model\ldcms\Models as ModelsModel;
use think\Cache;
use think\Db;
use think\Exception;

class Category extends Backend
{
    // 表名
    protected $name = 'ldcms_category';

    // 追加属性
    protected $append = [
        'url'
    ];

    public static function init()
    {
        self::beforeUpdate(function ($data) {
            if (!$data['status']) {
                $data['is_nav'] = 0;
            }
            $oldData = self::get($data['id']);

            /*更改类型，只有是单页的情况下删除内容*/
            if ($data['type']==1 && $data['mid'] == 1 ) {
                self::deleteOldData($data['mid'], $data['id']);
            }

            /*添加单页 并且 类型是模型的 */
            if($data['mid'] == 1 && $data['type']==0){
                $countpage=Db::name('ldcms_document')->where('cid',$data['id'])->count();

                /*如果是其他模型改成单页模型的 则将数据都删除*/
                if ($oldData['mid'] != $data['mid'] && $countpage>1){
                    self::deleteOldData($data['mid'], $data['id']);
                    $countpage=0;
                }

                if($countpage==0){
                    $pageData = [
                        'title' => $data['name'],
                        'content' => '',
                        'cid' => $data['id'],
                        'mid' => $data['mid'],
                        'show_time' => date('Y-m-d H:i:s')
                    ];
                    (new DocumentModel)->savePageData($pageData);
                }
            }

            /*更改了模型,同时更改内容数据的模型*/
            if($oldData['mid'] != $data['mid'] ){
                self::updateOldData($data['id'],$oldData['mid'],$data['mid']);
            }
        });

        /*删除前检测*/
        self::beforeDelete(function ($data) {
            /*判断是否有子栏目，如果有提示先删除子栏目*/
            $count = Db::name('ldcms_category')->where('pid', $data['id'])->count();
            if($count){
                throw new Exception("请先删除子栏目");
            }
        });

        self::afterDelete(function ($data) {
            /*同时删除该栏目下的内容*/
            self::deleteOldData($data['mid'],$data['id']);
        });
        //清除栏目缓存
        Cache::tag('category')->clear();
    }

    /*更新数据的模型*/
    private static function updateOldData($cid,$old_mid,$new_mid){
        $ids = Db::name('ldcms_document')->where('cid', $cid)->where('mid',$old_mid)->column('id');
        if (!empty($ids)) {
            (new DocumentModel())->where('id','in',$ids)->update(['mid'=>$new_mid]);
        }
    }

    /*真实删除旧数据*/
    private static function deleteOldData($mid,$cid){
        $ids = Db::name('ldcms_document')->where('cid', $cid)->column('id');
        if (!empty($ids)) {
            $models = (new ModelsModel())->getAdminList();
            (new DocumentModel())->destroyData($ids, $models[$mid]['table_name']);
        }
    }

    public function getUrlAttr($value, $data)
    {
        $vars = [
            ':id' => $data['id'],
            ':category' => $data['urlname'],
        ];
        if (isset($data['type']) && isset($data['outlink']) && $data['type'] == '1') {
            /*站内链接*/
            if(strpos($data['outlink'],'@')===0){
                $config = get_addon_config('ldcms');
                $rewrite = $config && isset($config['rewrite']) && $config['rewrite'] ? $config['rewrite'] : [];
                $list_rewrite=$rewrite['lists/index']??'';
                $outlink=str_replace('@','',$data['outlink']);
                if(strpos($list_rewrite,'ldcms')!==false){
                    return '/ldcms'.$outlink;
                }else{
                    return $outlink;
                }
            }
            return $this->getAttr('outlink');
        }
        return addon_url('ldcms/lists/index', $vars, true);
    }

    public function getAdminTreeList($mid = '')
    {
        $where = [];
        if (!empty($mid)) {
            $where['mid'] = $mid;
        }

        $data = $this->where('status', 1)
            ->where($where)
            ->where('lang', $this->getLang())
            ->field('id,pid,name,urlname,type,status')
            ->order($this->sort)->select();
        $data = $this->toSelectTree(collection($data)->toArray());
        return $data;
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
        $categorys = self::where('status', 1)->column('id,pid,name,mid', 'id');
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

    /** 列表转多维关联数组
     * @param $items  原始数据
     * @param string $pid  父ID
     * @param string $child_name 自定义子数组名称  默认child
     * @return array
     */
    public function toMoreTree($items, $pid = "pid", $child_name = 'child')
    {
        $map = [];
        $tree = [];
        foreach ($items as &$it) {
            $map[$it['id']] = & $it;
        } //数据的ID名生成新的引用索引树
        foreach ($items as &$it) {
            @$parent = & $map[$it[$pid]];
            if ($parent) {
                $parent[$child_name][] = & $it;
            }
            else {
                $tree[] = & $it;
            }
        }
        return $tree;
    }

    /** 数组转换成节点树 html
     * @param $list
     * @param int $pid
     * @param $level
     * @return array
     * @auther lxc
     */
    public function toSelectTree($list, $pid = 0, $level = 0)
    {
        $list = $this->toMoreTree($list, 'pid', 'child_arr');
        /*匿名函数实现递归生成html_tree*/
        $function = function ($list, $level) use (&$function) {
            $arr = array();
            $number = 1;
            $total = count($list);
            foreach ($list as $k => $v) {
                if ($level == 0) {
                    $v['icon_name'] = $v['name'];
                }
                elseif ($number == $total) {
                    $v['icon_name'] = html_entity_decode(str_repeat('&nbsp;&nbsp;', $level) . '└ ' . $v['name']);
                }
                else {
                    $v['icon_name'] = html_entity_decode(str_repeat('&nbsp;&nbsp;', $level) . '├ ' . $v['name']);
                }
                $child_arr = isset($v['child_arr']) ? $v['child_arr'] : '';
                unset($v['child_arr']);
                $arr[] = $v;
                if (!empty($child_arr)) {
                    $arr = array_merge($arr, $function($child_arr, $level + 1));
                }
                $number++;
            }
            return $arr;
        };
        return $function($list, $level);
    }

    /**
     * 验证上级id是否合法
     * @param $id
     * @param $pid
     */
    public static function validPid($id, $pid)
    {
        $childrenIds = (new self())->getChildrenIds($id, true);
        if (in_array($pid, $childrenIds)) {
            return false;
        }
        return true;
    }
}
