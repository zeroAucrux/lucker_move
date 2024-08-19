<?php


namespace addons\ldcms\controller\api;


use addons\ldcms\model\Ad;
use addons\ldcms\model\Category;
use addons\ldcms\model\Diyform;
use addons\ldcms\model\DiyformData;
use addons\ldcms\model\Document;
use addons\ldcms\model\Links;
use addons\ldcms\utils\Common;
use app\common\library\Email;
use think\Validate;

class V1 extends Base
{
    /*获取配置*/
    public function config()
    {
        $config=$this->siteConfig;
        $this->success('ok',$config);
    }

    /*获取导航*/
    public function nav(){
        $pid= $this->request->param('pid',0);
        $limit=$this->request->param('limit','');
        $data= Category::instance()->getHomeNav($pid,$limit);
        $this->success('ok',$data);
    }

    /*获取指定分类或子类*/
    public function category()
    {
        $cid= $this->request->param('cid',0);
        $pid= $this->request->param('pid',0);
        $limit=$this->request->param('limit','');
        $data=Category::instance()->getHomeCategory($cid,$pid,$limit);
        $this->success('ok',$data);
    }

    /*获取banner*/
    public function ad(){
        $name=$this->request->param('name');
        $data=Ad::instance()->getHomeSlide($name)->visible(['image','description','target','title','url']);
        $this->success('ok',$data);
    }

    /*获取列表*/
    public function lists()
    {
        $params=$this->request->param();
        $data=Document::instance()->getHomeList($params);
        $this->success('ok',$data);
    }

    /*获取内容*/
    public function content(){
        $cid=$this->request->param('cid');
        $data=Document::instance()->getHomeInfoByCId($cid);
        $this->success('ok',$data);
    }

    /*获取详情*/
    public function detail()
    {
        $id=$this->request->param('id');
        $data=Document::instance()->getHomeInfoById($id);
        $this->success('ok',$data);
    }

    /*友情链接*/
    public function links()
    {
        $name=$this->request->param('name');
        $data=Links::instance()->getHomeList($name);
        $this->success('ok',$data);
    }

    /*提交表单*/
    public function diyform()
    {
        $type=$this->request->param('type');
        $post=$this->request->post();
        /*验证表单模型是否存在*/
        $diyformModel= Diyform::instance();
        $find=$diyformModel->with(['fields'])->where('status',1)->where('name',$type)->find();
        if(empty($find)){
            $this->error(__('Unable to submit form')); //无法提交该表单
        }
        /*是否需要登录*/
        if ($find['needlogin'] && !$this->auth->isLogin()) {
            $this->error(__('Please login first')); //请登录后再操作
        }
        $mail_body='';
        foreach ($find['fields'] as $field){
            if(!empty($field['rule'])){
                $rule[$field['field'].'|'.__($field['field'])]=$field['rule'];
            }
            $mail_body.=$field['title'].':'.$post[$field['field']]."<br>";
        }
        $valiRes=$this->validate($post,$rule);
        if($valiRes!==true){
            $this->error($valiRes);
        }

        $post['diyform_id']=$find['id'];
        $post['user_os']=Common::get_user_os();
        $post['user_bs']=Common::get_user_bs();
        $post['user_ip']=request()->ip();
        $post['lang']='zh-cn';
        $post['create_time']=time();
        $post['update_time']=time();
        $diyformDataModel=DiyformData::instance()->name($find['table']);
        $res=$diyformDataModel->insert($post);
        if(!$res){
            $this->error(__('Submit failed')); //提交失败
        }

        if($this->addonConfig['is_form_email']&&!empty($this->addonConfig['to_email'])){
            $mail_subject = "【" . $this->siteConfig['sitetitle'] . "】您有新的信息，请注意查收！";
            $mail_body.= '<br>来自网站 ' . $this->request->host() . ' （' . date('Y-m-d H:i:s') . '）';
            $this->toEmail($this->addonConfig['to_email'],$mail_subject,$mail_body);
        }
        $this->success(__('Submitted successfully'));  //提交成功
    }

    /**
     * 发送邮件
     * @param $receiver
     * @param $subject
     * @param $html
     * @return bool
     */
    protected function toEmail($receiver,$subject,$html){
        if(empty($receiver)){
            $this->error(__('Invalid parameters'));
        }
        $receiver_arr=explode(',',$receiver);
        foreach ($receiver_arr as $item){
            if (!Validate::is($item, "email")) {
                $this->error(__('Please input correct email'));
            }
        }
        $email = new Email;
        $result = $email
            ->to($receiver)
            ->subject($subject)
            ->message($html)
            ->send();
        if ($result) {
            return true;
        } else {
            $this->error($email->getError());
        }
    }
}