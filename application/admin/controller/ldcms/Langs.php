<?php

namespace app\admin\controller\ldcms;

use addons\ldcms\utils\Dir;
use addons\ldcms\utils\builder\Form;
use Symfony\Component\VarExporter\VarExporter;
use think\Exception;

/**
 * 多语言管理
 *
 * @icon fa fa-circle-o
 */
class Langs extends Base
{

    /**
     * Langs模型对象
     * @var \app\admin\model\ldcms\Langs
     */
    protected $model = null;
    protected $modelValidate=true;
    protected $noNeedRight=['getLangContent','editLangFile','editDomain'];
    protected $multiFields=['status','is_default','sort','sub_title'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ldcms\Langs;

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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if($this->request->isPost()){
            $this->addPost();
        }else{
            return Form::instance()
                ->setHtml('<div class="alert alert-warning-light">
        <b>注意：</b>
添加语言后需要手动添加对应语言的<code>栏目</code>、<code>内容</code>，以及修改对应语言的<code>站点信息</code>和<code>前端语言文件</code>。
    </div>')
                ->setFormItem('title','名称','string','required')
                ->setFormItem('sub_title','简称','string')
                ->setFormItem('code','编码','string','required;length(2~10)')
                ->setFormItem('domain','绑定域名','string','length(2~30)')
                ->setFormItem('is_default','默认语言','switch')
                ->setFormItem('sort','排序','string')
                ->setFormItem('status','状态','switch','',['value'=>1])
                ->fetch();
        }
    }

    public function edit($ids=null)
    {
        if($this->request->isPost()){
            $idarr=explode(',',$ids);
            foreach ($idarr as $id){
                if(in_array($id,[1,2])){
                    $this->error('不能更改ID为"1,2"的数据');
                }
            }
            $this->editPost($ids);
        }else{
            $row=$this->model->get($ids);
            return Form::instance()
                ->setFormItemHidden('id')
                ->setFormItem('title','名称','string','required')
                ->setFormItem('sub_title','简称','string')
                ->setFormItem('code','编码','string','required;length(2~10)')
                ->setFormItem('domain','绑定域名','string','length(2~30)')
                ->setFormItem('is_default','默认语言','switch')
                ->setFormItem('sort','排序','string')
                ->setFormItem('status','状态','switch','',['value'=>1])
                ->values($row)
                ->fetch();
        }
    }

    public function del($ids=null)
    {
        $idarr=explode(',',$ids);
        if(count($idarr)<=0){
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }
        foreach ($idarr as $id){
            if(in_array($id,[1,2])){
                $this->error('不能删除ID为"1,2"的数据');
            }
        }
        parent::del($ids);
    }

    /*编辑语言文件*/
    public function editLangFile()
    {
        $code=$this->request->param('code');
        if(empty($code)){
            $this->error('未取到语言编码');
        }

        if($this->request->isPost()){
            $langarr=$this->request->post('row.lang_json/a');
            $file=$this->request->post('row.file');
            if(empty($file)){
                $this->error('语言文件不存在');
            }
            $lang_data=[];
            foreach ($langarr as $vo) {
                $lang_data[$vo['key']] = $vo['value'];
            }

            // 写入lang文件
            $res = file_put_contents(ADDON_PATH.'ldcms'.DS. $file, "<?php\n\n" . "return " . VarExporter::export($lang_data) . ";\n", LOCK_EX);
            if (!$res) {
                $this->error('文件写入失败 请确定lang目录有写入权限');
            }
            $this->success('保存成功');
        }else{
            $file='lang'.DS.$code.'.php';
            $this->assignconfig('file',$file);
            return Form::instance()
                ->setFormItem('type','类型','radio','',['content_list'=>['前台语言包']])
                ->setFormItem('file','','string','',['class'=>'hidden','value'=>$file])
                ->setFormItem('lang_content','内容','array','',[
                    'content_list'=>'',
                    'extend_html'=>'data-template="lang_tlp"'
                ])
                ->setHtml('<script type="text/html" id="lang_tlp">
        <dd class="form-inline">
            <input type="text" name="row[lang_json][<%=index%>][key]" class="form-control" value="<%=row.key%>" size="30">
            <input type="text" name="row[lang_json][<%=index%>][value]" class="form-control" value="<%=row.value%>" size="30">
            <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
        </dd>
    </script>')
                ->fetch();
        }
    }

    public function editDomain($ids=null){
        if($this->request->isPost()){
            $this->editPost($ids);
        }
    }

//    public function getLangFile()
//    {
//        $type=$this->request->param('custom.type');
//        $langfile=[];$path='';$typefile='';
//        switch ($type){
//            case 0:
//                $typefile='lang';
//                $path=ADDON_PATH.'ldcms'.DS.'lang';
//                break;
//            default:
//        }
//
//        $langfile=(new Dir($path))->toArray();
//        $langarr=[];
//        foreach ($langfile as $file){
//            $langarr[]=[
//                'id'=>$file['filename'],
//                'title'=>$typefile.DS.$file['filename']
//            ];
//        }
//        return json(['list' => $langarr, 'total' => count($langarr)]);
//    }

    public function getLangContent()
    {
        try {
            $lang_file = $this->request->param('lang_file');
            $path=ADDON_PATH.'ldcms'.DS;
            if (strstr($lang_file, '../') || strstr($lang_file, '..\\')) {
                throw new Exception("文件目录有误!!!");
            }
            $lang_var = require $path . $lang_file;
            if (gettype($lang_var) != 'array') {
                $this->error('加载文件有误~!!!');
            }
            $lang_data = [];
            foreach ($lang_var as $key => $vo) {
                $lang_data[] = [
                    'key'   => $key,
                    'value' => $vo,
                ];
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('语言文件数据加载成功', '', $lang_data);
    }
}
