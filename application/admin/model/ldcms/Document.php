<?php


namespace app\admin\model\ldcms;
use addons\ldcms\model\common\Backend;
use addons\ldcms\utils\AutoSql;
use addons\ldcms\utils\Common;
use app\admin\library\Auth;
use app\admin\model\ldcms\Fields as FieldsModel;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;
use traits\model\SoftDelete;

class Document extends Backend
{
    use SoftDelete;
// 表名
    protected $name = 'ldcms_document';
    protected $sort = 'sort ASC,id DESC';

    protected $type = [
        'show_time' => 'timestamp',
    ];
    protected $append = [
        'url', 'seo_description'
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
        $getStatus=$this->getStatus();
        return $getStatus[$data['status']];
    }

    public function getFullUrlAttr($value,$data)
    {
        return $this->buildUrl($value,$data,'html',true);
    }

    public function getUrlAttr($value, $data)
    {
       return $this->buildUrl($value,$data);
    }

    protected function buildUrl($value,$data,$suffix='html',$domain = false){
        if (empty($data['curlname'])) {
            return '';
        }
        $vars = [
            ':id' => $data['id'],
            ':category' => $data['curlname'],
        ];
        if (isset($data['outlink']) && !empty($data['outlink'])) {
            return $this->getAttr('outlink');
        }
        /*自定义url*/
        if(isset($data['custom_urlname'])&&!empty($data['custom_urlname'])){
            $vars[':id']=$data['custom_urlname'];
        }
        return addon_url('ldcms/detail/index', $vars ,$suffix,$domain);
    }

    public function info($table_name, $id)
    {
        $extend_table_name = 'ldcms_document_' . $table_name;
        $data= $this->alias('document')
            ->join('ldcms_category category', 'category.id=document.cid', 'LEFT')
            ->join('ldcms_document_content content', 'content.document_id=document.id', 'LEFT')
            ->join($extend_table_name . ' extend', 'extend.document_id=document.id', 'LEFT')
            ->field('document.*,extend.*,content.*,category.name cname,category.urlname curlname')
            ->find($id);
        return $data;
    }

    /**
     * 数据写入 content表中
     * @param $data
     * @return mixed
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function saveContent($data)
    {
        $document_id = $data['id'];
        $content = $data['content'];
        $contentDb = Db::name('ldcms_document_content');
        $res = true;
        if ($contentDb->find($document_id)) {
            $res = $contentDb->strict(false)->where('document_id', $document_id)->update(['content' => $content]);
        }
        else {
            $res = $contentDb->strict(false)->insert(['document_id' => $document_id, 'content' => $content]);
        }
        if ($res === false) {
            throw new Exception('保存content内容失败');
        }
        return true;
    }

    /**
     * 数据写入副表
     * @param $table_name
     * @param $data
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function savaExtend($table_name, $data)
    {
        $table_name = 'ldcms_document_' . $table_name;
        $document_id = $data['id'];
        $extendDb = Db::name($table_name);
        foreach ($data as $k => &$value) {
            if (is_array($value) && is_array(reset($value))) {
                $value = json_encode(self::getArrayData($value), JSON_UNESCAPED_UNICODE);
            } else {
                $value = is_array($value) ? implode(',', $value) : $value;
            }
        }

        $res = true;
        if ($extendDb->find($document_id)) {
            $res = $extendDb->strict(false)->where('document_id', $document_id)->update($data);
        }
        else {
            $data['document_id'] = $document_id;
            $res = $extendDb->strict(false)->insert($data);
        }

        if ($res === false) {
            throw new Exception('保存副表内容失败');
        }
        return true;
    }

    public static function getArrayData($data)
    {
        if (!isset($data['value'])) {
            $result = [];
            foreach ($data as $index => $datum) {
                $result['field'][$index] = $datum['key'];
                $result['value'][$index] = $datum['value'];
            }
            $data = $result;
        }
        $fieldarr = $valuearr = [];
        $field = isset($data['field']) ? $data['field'] : (isset($data['key']) ? $data['key'] : []);
        $value = isset($data['value']) ? $data['value'] : [];
        foreach ($field as $m => $n) {
            if ($n != '') {
                $fieldarr[] = $field[$m];
                $valuearr[] = $value[$m];
            }
        }
        return $fieldarr ? array_combine($fieldarr, $valuearr) : [];
    }

    /**
     * 保存page数据
     * @param $data
     */
    public function savePageData($data)
    {
        Db::startTrans();
        try {
            /*获取并合并单页扩展字段*/
            $fieldsData = [];
            $fields = (new FieldsModel())->getAdminList(1);
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $fieldsData[$field['field']] = '';
                }
                $data = array_merge($data, $fieldsData);
            }

            $res = $this->allowField(true)->save($data);
            if ($res === false) {
                throw new Exception('写入单页内容失败');
            }
            $data['id'] = $this->id;
            /*数据写入cntent*/
            $this->saveContent($data);
            /*数据写入扩展表*/
            $this->savaExtend('page', $data);
            Db::commit();
            return true;
        }
        catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 真实删除
     * @param $ids
     */
    public function destroyData($ids, $table_name)
    {
        Db::startTrans();
        try {
            if (empty($ids)) {
                $ids = $this->onlyTrashed()->column('id');
            }
            $tags=Db::name('ldcms_document')->where('id', 'in', $ids)->column('tag');
            Db::name('ldcms_document')->where('id', 'in', $ids)->delete(true);
            /*删除content*/
            Db::name('ldcms_document_content')->where('document_id', 'in', $ids)->delete();
            /*删除副表*/
            Db::name('ldcms_document_' . $table_name)->where('document_id', 'in', $ids)->delete();
            Db::commit();

            foreach ($tags as $tag){
                Tags::instance()->insertTag('',$tag);
            }
            return true;
        }
        catch (PDOException|Exception $e) {
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /*描述*/
    public function setSeoDescriptionAttr($value, $data)
    {
        if (!empty($value)) {
            return $value;
        }
        if (empty($data['content'])) {
            return '';
        }
        $content = strip_tags(htmlspecialchars_decode($data['content']));
        return Common::substr_cut($content, 100);
    }

    public function setFlagAttr($value,$data)
    {
        if(empty($value)){
            return '';
        }
        return implode(',',$value);
    }

    public function setSubCidAttr($value,$data)
    {
        if(empty($value)){
            return '';
        }
        if(!is_array($value)){
            return $value;
        }
        return implode(',',$value);
    }

    public function setAuthorAttr($value,$data)
    {
        if(empty($value)){
            $auth= new Auth();
            $userInfo=$auth->getUserInfo();
            return $userInfo['nickname'];
        }
        return $value;
    }
}