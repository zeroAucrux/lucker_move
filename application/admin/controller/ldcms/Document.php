<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\Baidupush;
use addons\ldcms\utils\builder\Form;
use addons\ldcms\utils\DfaFilter\SensitiveHelper;
use addons\ldcms\utils\fanyi\youdao\Youdao;
use app\admin\model\ldcms\Category as CategoryModel;
use app\admin\model\ldcms\Document as DocumentModel;
use app\admin\model\ldcms\Fields as FieldsModel;
use app\admin\model\ldcms\Models as ModelsModel;
use app\admin\model\ldcms\Tags;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 文章基础管理
 *
 * @icon fa fa-circle-o
 */
class Document extends Base
{

    /**
     * Document模型对象
     * @var DocumentModel
     */
    protected $model = null;
    protected $models = null;
    protected $mid = 0;
    protected $categorys = null;
    protected $category_for_name = null;
    protected $fields = null;
    protected $extend_table_name = null;
    protected $mnames = [];
    protected $searchFields = 'title';
    protected $multiFields = "status,sort";
    protected $noNeedRight = ['category', 'getTags', 'getUserLevels'];
    protected $addonConfig = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new DocumentModel;
        $this->models = (new ModelsModel())->getAdminList();
        $first_key = array_key_first($this->models);
        $this->mid = $this->request->param('mid', $first_key);
        $this->categorys = (new CategoryModel())->getAdminTreeList($this->mid);

        foreach ($this->categorys as &$cate) {
//            if($cate['type']==1) { continue;}
            $this->category_for_name[$cate['id']] = $cate['icon_name'];
        }
        unset($cate);

        $this->fields = (new FieldsModel())->getAdminList($this->mid);
        $this->extend_table_name = $this->models[$this->mid]['table_name'];
        $this->view->assign('mid', $this->mid);
        $this->view->assign('models', $this->models);
        $this->view->assign('fields', $this->fields);
        $this->assignconfig('mname', $this->models[$this->mid]['name']);
        $this->assignconfig('status', $this->model->getStatus());
        $this->assignconfig('flags', config('ldcms.flags'));
        $this->assignconfig('mid', $this->mid);
        $this->addonConfig = get_addon_config('ldcms');
        $this->assignconfig('sensitive_check', $this->addonConfig['sensitive_check']);
        $this->assignconfig('category_for_name', $this->category_for_name);
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            /*修改选择栏目包含子栏目*/
            $filter = json_decode($this->request->get('filter'), true);
            if (isset($filter['cid'])) {
                $filter['cid'] = CategoryModel::instance()->getChildrenIds($filter['cid'], true);
                $this->request->get(['filter' => json_encode($filter)]);
            }
            /*去掉自动生成的状态where*/
            $whereExt = [];
            if (isset($filter['status'])) {
                $whereExt['document.status'] = $filter['status'];
                unset($filter['status']);
                $this->request->get(['filter' => json_encode($filter)]);
            }
            if (isset($filter['gid'])) {
                $whereExt['document.gid'] = $filter['gid'];
                unset($filter['gid']);
                $this->request->get(['filter' => json_encode($filter)]);
            }
            list($where, $sort, $order, $offset, $limit, $alias) = $this->buildparams();
            $extend_table_name = 'ldcms_document_' . $this->extend_table_name;
            $list = $this->model
                ->alias('document')
                ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
                ->join($extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT')
                ->field('document.*,extend.*,category.name cname,category.urlname curlname')
                ->where('document.mid', $this->mid)
                ->where('document.lang', $this->lang)
                ->where($whereExt)
                ->where($where)
                ->order($this->model->getSort())
                ->paginate($limit);

            foreach ($list->items() as $item) {
                $item->append(['status_text']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        $this->assignconfig('fields', $this->fields);
        $this->view->assign('categorys', $this->categorys);
        return $this->view->fetch('index');
    }

    public function category()
    {
        return ['rows' => $this->categorys, 'total' => count($this->categorys)];
    }

    public function getTags()
    {
        $q = $this->request->request('q');
        $tags = Tags::instance()->where('title', 'like', '%' . $q . '%')->column('title');
        return $tags;
    }

    public function add()
    {
        if ($this->request->post()) {
            $params = $this->request->post('row/a');
            if (empty($params)) {
                $this->error(__('Parameter %s can not be empty', ''));
            }
            $params = $this->preExcludeFields($params);
            if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                $params[$this->dataLimitField] = $this->auth->id;
            }
            $params['mid'] = $this->mid;
            $result = false;
            Db::startTrans();
            try {
                //是否采用模型验证
                if ($this->modelValidate) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                    $this->model->validateFailException()->validate($validate);
                }
                $params['lang'] = ld_get_lang();
                $result = $this->model->allowField(true)->save($params);
                $params['id'] = $this->model->id;
                /*数据写入content*/
                $this->model->saveContent($params);
                /*数据写入扩展表*/
                $this->model->savaExtend($this->extend_table_name, $params);
                /*写入tag表*/
                if (!empty($params['tag'])) {
                    Tags::instance()->insertTag($params['tag']);
                }
                Db::commit();
            } catch (ValidateException | PDOException | Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result === false) {
                $this->error(__('No rows were inserted'));
            }
            /*百度推送*/
            if ($this->addonConfig['baidupush']) {
                $row = $this->model->info($this->extend_table_name, $params['id']);
                $urls = [$row['fullurl']];
                Baidupush::instance()->normal($urls);
            }

            $this->success();
        }


        return Form::instance()
            ->fieldShow()
            ->setGroup('基础')
            ->setFormItem('cid', '栏目', 'select', 'required', ['content_list' => $this->category_for_name,'extend_html'=>'data-live-search="true"'])
            ->setFormItem('title', '标题', 'string', 'required')
            ->setFormItem('content', '内容', 'editor','',$this->isFormHidden('content'))
            ->setFormItem('image', '缩略图', 'image','',$this->isFormHidden('image'))
            ->setFormItem('pics', '多图', 'images','',$this->isFormHidden('pics'))
            ->setFormItems($this->fields)
            ->setFormItem('flag', '标识', 'selects', '', ['content_list' => config('ldcms.flags')])
            ->setFormItem('tag', 'TAG标签', 'tags')
            ->setFormItem('gid', __('浏览权限'), 'selectpages', '', [
                'content_list' => url('ldcms/document/getUserLevels'),
                'setting' => ['primarykey' => 'id', 'field' => 'title'],
                'placeholder' => '默认为公开'
            ])
            ->setFormItem('author', '作者', 'string', '', [
                'value' => $this->userinfo['nickname']
            ])
            ->setFormItem('admin_id', 'admin_id', 'string', '', ['class' => 'hidden', 'value' => $this->userinfo['id']])
            ->setGroup('高级')
            ->setFormItem('sub_cid', '副栏目', 'selects', '', function($data){
                return array_merge($data,['content_list' => $this->category_for_name],$this->isFormHidden('sub_cid'));
            })
            ->setFormItem('custom_tpl', '自定义模版', 'selectpage','',function($data){
                $data['content_list']=json_encode(ModelsModel::instance()->getTpl('detail'));
                return array_merge($data,$this->isFormHidden('custom_tpl'));
            })
            ->setFormItem('custom_urlname', '自定义URL', 'string','',$this->isFormHidden('custom_urlname'))
            ->setFormItem('seo_keywords', '关键词', 'string','',$this->isFormHidden('seo_keywords'))
            ->setFormItem('seo_description', '描述', 'text','',$this->isFormHidden('seo_description'))
            ->setFormItem('visits', '浏览次数', 'string')
            ->setFormItem('outlink', '跳转链接', 'string','',$this->isFormHidden('outlink'))
            ->setFormItem('show_time', '发布时间', 'datetime', '', ['value' => date('Y-m-d H:i:s')])
            ->setFormItem('status', '状态', 'switch', '', ['value' => 1])
            ->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->info($this->extend_table_name, $ids);
        if ($this->request->post()) {
            $params = $this->request->post('row/a');
            if (empty($params)) {
                $this->error(__('Parameter %s can not be empty', ''));
            }
            $params = $this->preExcludeFields($params);
            if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                $params[$this->dataLimitField] = $this->auth->id;
            }
            $params['mid'] = $this->mid;
            $result = false;
            Db::startTrans();
            try {
                //是否采用模型验证
                if ($this->modelValidate) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                    $this->model->validateFailException()->validate($validate);
                }

                $result = $this->model::update($params, ['id' => $params['id']], true);
                /*数据写入content*/
                $this->model->saveContent($params);
                /*数据写入扩展表*/
                $this->model->savaExtend($this->extend_table_name, $params);

                /*写入tag表*/
                Tags::instance()->insertTag($params['tag'], $row['tag']);

                Db::commit();
            } catch (ValidateException | PDOException | Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result === false) {
                $this->error(__('No rows were inserted'));
            }

            /*百度推送*/
            if ($row['status'] == 0 && $params['status'] == 1) {
                if ($this->addonConfig['baidupush']) {
                    $urls = [$row['fullurl']];
                    Baidupush::instance()->normal($urls);
                }
            }

            $this->success();
        }
        return Form::instance()
            ->fieldShow()
            ->setGroup('基础')
            ->setFormItem('id', '', 'string', '', ['class' => 'hidden'])
            ->setFormItem('cid', '栏目', 'select', 'required', ['content_list' => $this->category_for_name,'extend_html'=>'data-live-search="true"'])
            ->setFormItem('title', '标题', 'string', 'required')
            ->setFormItem('content', '内容', 'editor','',$this->isFormHidden('content'))
            ->setFormItem('image', '缩略图', 'image','',$this->isFormHidden('image'))
            ->setFormItem('pics', '多图', 'images','',$this->isFormHidden('pics'))
            ->setFormItems($this->fields)
            ->setFormItem('flag', '标识', 'selects', '', ['content_list' => config('ldcms.flags')])
            ->setFormItem('tag', 'TAG标签', 'tags')
            ->setFormItem('gid', __('浏览权限'), 'selectpages', '', [
                'content_list' => url('ldcms/document/getUserLevels'),
                'setting' => ['primarykey' => 'id', 'field' => 'title'],
                'placeholder' => '默认为公开'
            ])
            ->setFormItem('author', '作者', 'string', '', [
                'value' => $this->userinfo['nickname']
            ])
            ->setFormItem('admin_id', 'admin_id', 'string', '', ['class' => 'hidden', 'value' => $this->userinfo['id']])
            ->setGroup('高级')
            ->setFormItem('sub_cid', '副栏目', 'selects', '', function($data){
                return array_merge($data,['content_list' => $this->category_for_name],$this->isFormHidden('sub_cid'));
            })
            ->setFormItem('custom_tpl', '自定义模版', 'selectpage','',function($data){
                $data['content_list']=json_encode(ModelsModel::instance()->getTpl('detail'));
                return array_merge($data,$this->isFormHidden('custom_tpl'));
            })
            ->setFormItem('custom_urlname', '自定义URL', 'string','',$this->isFormHidden('custom_urlname'))
            ->setFormItem('seo_keywords', '关键词', 'string','',$this->isFormHidden('seo_keywords'))
            ->setFormItem('seo_description', '描述', 'text','',$this->isFormHidden('seo_description'))
            ->setFormItem('visits', '浏览次数', 'string')
            ->setFormItem('outlink', '跳转链接', 'string','',$this->isFormHidden('outlink'))
            ->setFormItem('show_time', '发布时间', 'datetime', '', ['value' => date('Y-m-d H:i:s')])
            ->setFormItem('status', '状态', 'switch', '', ['value' => 1])
            ->values($row)
            ->fetch();
    }

    /**
     * 真实删除
     * @param string $ids
     */
    public function destroy($ids = "")
    {
        $ids = $ids ?: $this->request->post('ids');
        $res = (new DocumentModel())->destroyData($ids, $this->extend_table_name);
        if ($res) {
            $this->success('删除成功');
        }
    }


    /**
     * 移动
     * @param string $ids
     */
    public function move($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        if ($ids) {
            $cid = $this->request->post('cid');
            $res = $this->model->where('id', 'in', $ids)->update(['cid' => $cid]);
            if ($res === false) {
                $this->error('移动失败');
            }
            $this->success('移动内容成功');
        } else {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }
    }

    /**
     * 复制
     * @param string $ids
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function copy($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        if ($ids) {
            $datas = $this->model->where('id', 'in', $ids)->select();
            foreach ($datas as $key => $item) {
                Db::startTrans();
                try {
                    $data = $item->toArray();
                    $data['title'] .= '_copy';
                    unset($data['id']);
                    unset($data['create_time']);
                    unset($data['update_time']);

                    $res = $this->model::create($data, true);
                    $id = $res->id;
                    $content_data = Db::name('ldcms_document_content')->where('document_id', $item['id'])->field('content')->find();
                    $extend_data = Db::name('ldcms_document_' . $this->extend_table_name)->where('document_id', $item['id'])
                        ->field('document_id', true)->find();
                    /*数据写入content*/
                    $content_data['id'] = $id;
                    $this->model->saveContent($content_data);
                    /*数据写入扩展表*/
                    $extend_data['id'] = $id;

                    $this->model->savaExtend($this->extend_table_name, $extend_data);
                    Db::commit();
                } catch (ValidateException | PDOException | Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            }
            $this->success();
        }
    }

    /*百度推送*/
    public function baidupush()
    {
        $param = $this->request->param();
        if (!isset($param['type'])) {
            $this->error('请传入推送类型');
        }
        if (empty($param['ids'])) {
            $this->error('请选择要推送的数据');
        }
        $type = $param['type'];
        $ids = $param['ids'];

        $list = $this->model
            ->alias('document')
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->field('document.*,category.name cname,category.urlname curlname')
            ->where('document.lang', $this->lang)
            ->where('document.id', 'in', $ids)
            ->select();
        $urls = [];
        foreach ($list as $item) {
            $urls[] = $item->fullurl;
        }
        /*普通推送*/
        if ($type == 'zz') {
            $res = Baidupush::instance()->normal($urls);
        } else {
            $res = Baidupush::instance()->daily($urls);
        }
        if ($res) {
            $resdata = Baidupush::instance()->getData();
            $this->success('成功推送' . $resdata['success'] . '条，今天剩余可推送' . $resdata['remain'] . '条数!');
        }
        $this->error('百度推送发生错误：' . Baidupush::instance()->getError());
    }

    /**
     * 敏感词查询
     */
    public function sensitive()
    {
        if ($this->request->isPost()) {
            $fields = $this->request->post('fileds/a');
            if (empty($fields)) { //没有数据直接返回
                $this->success();
            }
            $config = get_addon_config('ldcms');
            if (!empty($config['sensitive'])) {
                $wordData = explode(',', $config['sensitive']);
                $handle = SensitiveHelper::init()->setTree($wordData);
                $errors = [];
                foreach ($fields as $key => $field) {
                    $content = ld_clear_html($field['content']);
                    /*是否有敏感词*/
                    $islegal = $handle->islegal($content);
                    if ($islegal) {
                        /*标记敏感词*/
                        try {
                            $markedContent = $handle->mark($content, '<mark style="color:red">', '</mark>');
                            $field['content'] = $markedContent;
                            $errors[$key] = $field;
                        } catch (\Exception $exception) {
                        }
                    }
                }
                if (empty($errors)) {
                    $this->success();
                }
                $this->error('找到敏感词', '', $errors);
            }
        }
    }

    protected function isFormHidden($field){
        /*主表显示的字段*/
        $documentFields=ModelsModel::instance()->getShowDocumentFields($this->mid);
        if(!in_array($field,$documentFields)){
            return ['class'=>'hidden'];
        }
        return [];
    }

    /*翻译数据*/
    public function translate()
    {
        if ($this->request->isPost()) {
            $param = $this->request->param();
            if (empty($param['ids'])) {
                $this->error(__('Please select the data to be translated'));
            }

            if (empty($param['from_lang'])) {
                $this->error(__('Please select the source language'));
            }
            if (empty($param['to_lang'])) {
                $this->error(__('Please select the target language'));
            }
            $ids=$param['ids'];
            $from_lang=$param['from_lang'];
            $to_lang=$param['to_lang'];
            if($to_lang==$from_lang){
                $this->error(__('Two languages cannot be the same'));
            }

            $list = $this->model
                ->alias('document')
                ->join('ldcms_document_content content', 'content.document_id=document.id','LEFT')
                ->join('ldcms_document_'.$this->extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT')
                ->field('document.*,content.content content,extend.*')
                ->where('document.lang', $this->lang)
                ->where('document.id', 'in', $ids)
                ->select();
            try {
                $youdaoapi = new Youdao($this->addon_config['youdao_appKey'], $this->addon_config['youdao_appSecret']);

                $fieldtexts = [
                    'title',
                    'seo_keywords',
                    'seo_description',
                ];
                $fieldhtmls = [
                    'content',
                ];
                /*扩展字段*/
                foreach ($this->fields as $field) {
                    if ($field['type'] == 'editor') {
                        $fieldhtmls[] = $field['field'];
                    }
                    if (in_array($field['type'], ['string', 'text'])) {
                        $fieldtexts[] = $field['field'];
                    }
                }

                $from = $youdaoapi->getLangCode($from_lang);
                $to   = $youdaoapi->getLangCode($to_lang);
                foreach ($list as $item) {
                    $textdata = [];
                    $up_data  = [
                        'id' => $item['id'],
                    ];
                    foreach ($fieldtexts as $fieldtext) {
                        if (empty($item[$fieldtext])) continue;
                        $textdata[$fieldtext] = trim($item[$fieldtext]);
                    }
                    /*批量翻译字段内容*/
                    $resdata = $youdaoapi->translateBatch($textdata, $from, $to);
                    $up_data = array_merge($up_data, $resdata);

                    /*翻译扩展字段*/
                    foreach ($fieldhtmls as $fieldhtml) {
                        if (empty($item[$fieldhtml])) continue;
                        $up_data[$fieldhtml] = $youdaoapi->translateHtml($item[$fieldhtml], $from, $to);
                    }

                    Db::startTrans();
                    try {
                        $this->model::update($up_data, ['id' => $up_data['id']], true);
                        /*数据写入content*/
                        $this->model->saveContent($up_data);
                        /*数据写入扩展表*/
                        $this->model->savaExtend($this->extend_table_name, $up_data);
                        Db::commit();
                    } catch (ValidateException|PDOException|Exception $e) {
                        Db::rollback();
                        trace($e->getMessage(), 'error');
                    }
                }
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            $this->success('翻译成功');
        }else{
            $this->assign('langs',\addons\ldcms\model\Langs::instance()->lists());
            $this->assign('lang',$this->lang);
            return $this->fetch();
        }
    }
}