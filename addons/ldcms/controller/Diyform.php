<?php


namespace addons\ldcms\controller;


use addons\ldcms\model\DiyformData;
use addons\ldcms\utils\Common;
use app\common\library\Email;
use think\Validate;

class Diyform extends Base
{
    public function __call($action,$arg)
    {
        $post=$this->request->post();
        $token=$post['__token__'];
        //验证Token
        if (!$token || !\think\Validate::is($token, "token", ['__token__' => $token])) {
            $this->error(__('Illegal request')); //非法请求
        }
        /*验证表单模型是否存在*/
        $diyformModel=\addons\ldcms\model\Diyform::instance();
        $find=$diyformModel->with(['fields'])->where('status',1)->where('name',$action)->find();
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
                $rule[$field['field'].'|'.__($field['title'])]=$field['rule'];
            }
            $mail_body.=$field['title'].':'.$post[$field['field']]."<br>";
        }

        $valiRes=$this->validate($post,$rule);
        if($valiRes!==true){
            $this->error($valiRes);
        }
        /*是否开启验证码*/
        if($find['iscaptcha']){
            $captcha = $post['captcha'];
            if(empty($captcha)){
                $this->error(__('Verification code is required')); //验证码必填
            }
            if (!captcha_check($captcha)) {
                $this->error(__('Verification code error')); //验证码不正确
            }
        }

        $post['diyform_id']=$find['id'];
        $post['user_os']=Common::get_user_os();
        $post['user_bs']=Common::get_user_bs();
        $post['user_ip']=request()->ip();
        $post['lang']=$this->lang;
        $post['create_time']=time();
        $post['update_time']=time();
        $diyformDataModel=DiyformData::instance()->name($find['table']);
        $res=$diyformDataModel->strict(false)->insert($post);
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