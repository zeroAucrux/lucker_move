<?php
/**
 * 跨语言复制数据
 */

namespace app\admin\controller\ldcms;


use addons\ldcms\model\Langs;
use app\admin\model\ldcms\Category as CategoryModel;
use app\admin\model\ldcms\Document as DocumentModel;
use app\admin\model\ldcms\Models as ModelsModel;
use fast\Tree;
use think\Cache;
use think\Db;
use app\admin\validate\ldcms\Category as CategoryValidate;
use think\Exception;
use think\exception\ValidateException;

class CopyLangs extends Base
{
    /**
     * @var DocumentModel
     */
    protected $documentModel = null;
    /**
     * @var CategoryModel
     */
    protected $categoryModel = null;
    protected $noNeedRight = ['getCategoryData', 'getCategoryOfLang'];


    public function _initialize()
    {
        parent::_initialize();
        $this->categoryModel = new CategoryModel();
        $this->documentModel = new DocumentModel();
    }

    /**
     * 复制栏目数据
     */
    public function category()
    {
        if ($this->request->isPost()) {
            Db::startTrans();
            try {
                $select_lang = $this->request->post('select_lang');
                $ids         = $this->request->post('ids/a');
                $res         = $this->categoryModel->where('lang', $select_lang)->where('id', 'in', $ids)->select();
                $lang        = $this->lang;
                $map         = [];
                $data        = [];
                /*整理数据*/
                if (!collection($res)->isEmpty()) {
                    foreach ($res as $item) {
                        $row                         = $item->toArray();
                        $urlname                     = $item['urlname'] . '_' . $lang;
                        $map[$urlname][$select_lang] = $item['id'];
                        $row['urlname']              = $urlname;
                        $row['lang']                 = $lang;
                        unset($row['id']);
                        unset($row['create_time']);
                        unset($row['update_time']);
                        unset($row['url']);
                        $data[] = $row;
                    }
                }

                /*导入数据*/
                $newres = $this->categoryModel->validate(CategoryValidate::class)->validateFailException()->saveAll($data);
                /*批量修改pid*/
                if ($newres) {
                    foreach ($newres as $item) {
                        $map[$item['urlname']][$lang] = $item['id'];
                        $oldcid                       = $map[$item['urlname']][$select_lang];
                        $this->copyDocument($item['mid'], $oldcid, $item['id'], $select_lang, $lang);
                    }
                    Cache::set('category_map', $map);
                    foreach ($newres as $item) {
                        if ($item['pid'] != 0) {
                            /*先全部更新pid 为0*/
                            Db::name('ldcms_category')->where('id', $item['id'])->update(['pid' => 0]);
                            /*如果map中有对应的pid 再更新成对应的pid*/
                            foreach ($map as $mapi) {
                                if ($mapi[$select_lang] == $item['pid']) {
                                    Db::name('ldcms_category')->where('id', $item['id'])->update(['pid' => $mapi[$lang]]);
                                }
                            }
                        }
                    }
                }
                Db::commit();
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (\Exception $e) {
                Db::rollback();
                $this->error('复制数据失败');
            }
            $this->success('复制成功');
        } else {
            $langs = Langs::instance()->getList();  //语言列表
            $this->assign('langs', $langs);
            $select_langs = $langs; //复制到的语言列表
            unset($select_langs[$this->lang]); //删除当前语言
            $this->assign('select_langs', $select_langs);
            return $this->view->fetch();
        }
    }

    /*获取栏目数据*/
    protected function copyDocument($mid, $oldcid, $newcid, $oldlang, $newlang, $ids = [])
    {
        $models     = (new ModelsModel())->getAdminList();
        $table_name = $models[$mid]['table_name'];
        list($where, $sort, $order, $offset, $limit, $alias) = $this->buildparams();
        $extend_table_name = 'ldcms_document_' . $table_name;
        $documentSql       = $this->documentModel
            ->alias('document')
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->join('ldcms_document_content content', 'content.document_id=document.id', 'LEFT')
            ->join($extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT')
            ->field('document.*,extend.*,content.*,category.name cname,category.urlname curlname')
            ->where('document.mid', $mid)
            ->where('document.cid', $oldcid)
            ->where('document.lang', $oldlang)
            ->order('sort ASC,id ASC')
            ->where($where);
        if (!empty($ids)) {
            $documentSql->where('document.id', 'in', $ids);
        }
        $list = $documentSql->select();
        foreach ($list as $item) {
            $itemarr         = $item->toArray();
            $itemarr['lang'] = $newlang;
            $itemarr['sub_cid']=$this->getNewSubCid($itemarr['sub_cid']);
            Db::startTrans();
            try {
                unset($itemarr['id']);
                unset($itemarr['create_time']);
                unset($itemarr['update_time']);
                $itemarr['cid'] = $newcid;
                $document       = $this->documentModel::create($itemarr, true);
                $itemarr['id']  = $document->id;

                /*数据写入content*/
                $this->documentModel->saveContent($itemarr);
                /*数据写入扩展表*/
                $this->documentModel->savaExtend($table_name, $itemarr);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        }
    }

    /*获取内容数据*/

    public function getCategoryData()
    {
        $filter = $this->request->get("filter", '');
        $op     = $this->request->get("op", '', 'trim');
        $filter = (array)json_decode($filter, true);
        $op     = (array)json_decode($op, true);
        $filter = $filter ? $filter : [];
        $lang   = $filter['lg'] ?? 'zh-cn';
        $tree   = Tree::instance();
        $tree->init(collection($this->categoryModel->where('lang', $lang)->order($this->categoryModel->getSort())->select())->toArray());
        $categorys = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $result    = array("total" => count($categorys), "rows" => $categorys);
        return json($result);
    }

    public function document()
    {
        $mid = $this->request->get('mid');
        if ($this->request->isPost()) {
            $select_lang = $this->request->post('select_lang');
            $to_lang     = $this->lang;
            $mid         = $this->request->post('mid');
            $ids         = $this->request->post('ids/a');
            $oldcid      = $this->request->post('old_category');
            $newcid      = $this->request->post('new_category');
            Db::startTrans();
            try {
                $this->copyDocument($mid, $oldcid, $newcid, $select_lang, $to_lang, $ids);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error('复制数据失败');
            }
            $this->success('复制成功');
        } else {
            $langs  = Langs::instance()->getList();  //语言列表
            $models = (new ModelsModel())->getAdminList();
            $this->assignconfig('mname', $models[$mid]['name']);
            $this->assign('langs', $langs);
            //复制到的语言列表
            $select_langs = $langs;
            //删除当前语言
            unset($select_langs[$this->lang]);
            $this->assign('select_langs', $select_langs);
            $this->assignconfig('lang', $this->lang);
            $this->assignconfig('mid', $mid);
            $this->assign('mid', $mid);

            return $this->view->fetch();
        }
    }

    /*复制内容*/

    public function getDocumentData()
    {
        $mid    = $this->request->get('mid');
        $filter = $this->request->get("filter", '');
        $op     = $this->request->get("op", '', 'trim');
        $filter = (array)json_decode($filter, true);
        $op     = (array)json_decode($op, true);
        $filter = $filter ? $filter : [];
        $lang   = $filter['lg'] ?? 'zh-cn';
        $cid    = $filter['cid'] ?? 0;
        $this->request->get(['filter' => '']);
        list($where, $sort, $order, $offset, $limit, $alias) = $this->buildparams();
        $documentSql = $this->documentModel
            ->alias('document')
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->field('document.*,category.name cname,category.urlname curlname')
            ->where('document.mid', $mid)
            ->where('document.lang', $lang);
        if ($cid) {
            $documentSql->where('document.cid', $cid);
        }

        $list = $documentSql->order($this->documentModel->getSort())
            ->where($where)
            ->paginate($limit);

        $result = array("total" => $list->total(), "rows" => $list->items());

        return json($result);
    }

    /*根据语言获取分类 树节点*/
    public function getCategoryOfLang()
    {
        $custom          = $this->request->param('custom/a');
        $mid             = $this->request->param('mid');
        $where['mid']    = $mid;
        $where['lang']   = $custom['lang'];
        $data            = $this->categoryModel
            ->where($where)
            ->field('id,pid,name,urlname,type,status')
            ->order($this->categoryModel->getSort())->select();
        $this->categorys = $this->categoryModel->toSelectTree(collection($data)->toArray());
        return ['rows' => $this->categorys, 'total' => count($this->categorys)];
    }

    /*获取新的sub_id*/
    protected function getNewSubCid($old_sub_cid){
        $urlnames=$this->categoryModel->where('id','in',$old_sub_cid)->column('urlname');
        $newurlnames=[];
        foreach ($urlnames as $urlname){
            $newurlnames[]=$urlname.'_'.$this->lang;
        }
        $cids=$this->categoryModel->where('urlname','in',$newurlnames)->column('id');
        return implode(',',$cids);
    }
}