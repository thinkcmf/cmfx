<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class MailerController extends AdminbaseController {

    // SMTP配置
    public function index() {
    	$this->display();
    }
    
    // SMTP配置处理
    public function index_post() {
    	$post = array_map('trim', I('post.'));
    	
    	if(in_array('', $post) && !empty($post['smtpsecure'])) $this->error("不能留空！");
    	
    	$configs['SP_MAIL_ADDRESS'] = $post['address'];
    	$configs['SP_MAIL_SENDER'] = $post['sender'];
    	$configs['SP_MAIL_SMTP'] = $post['smtp'];
		$configs['SP_MAIL_SECURE'] = $post['smtpsecure'];
    	$configs['SP_MAIL_SMTP_PORT'] = $post['smtp_port'];
    	$configs['SP_MAIL_LOGINNAME'] = $post['loginname'];
    	$configs['SP_MAIL_PASSWORD'] = $post['password'];
    	$result=sp_set_dynamic_config($configs);
    	sp_clear_cache();
    	if ($result) {
    		$this->success("保存成功！");
    	} else {
    		$this->error("保存失败！");
    	}
    }
    
    // 会员注册邮件模板
    public function active(){
    	$where = array('option_name'=>'member_email_active');
    	$option = M('Options')->where($where)->find();
    	if($option){
    		$options = json_decode($option['option_value'], true);
    		$this->assign('options', $options);
    	}
    	$this->display();
    }
    
    // 会员注册邮件模板提交
    public function active_post(){
        $configs=array();
    	$configs['SP_MEMBER_EMAIL_ACTIVE'] = I('post.lightup',0,'intval');
    	sp_set_dynamic_config($configs);

    	$data=array();
    	$data['option_name'] = "member_email_active";
    	$options=I('post.options/a');
    	$options['template']=htmlspecialchars_decode($options['template']);
    	$data['option_value']= json_encode($options);
    	$options_model= M('Options');
    	if($options_model->where("option_name='member_email_active'")->find()){
    		$result = $options_model->where("option_name='member_email_active'")->save($data);
    	}else{
    		$result = $options_model->add($data);
    	}
    	
    	if ($result!==false) {
    		$this->success("保存成功！");
    	} else {
    		$this->error("保存失败！");
    	}
    }
    
    // 邮件发送测试
    public function test(){
        if(IS_POST){
            $rules = array(
                 array('to','require','收件箱不能为空！',1,'regex',3),
                 array('to','email','收件箱格式不正确！',1,'regex',3),
                 array('subject','require','标题不能为空！',1,'regex',3),
                 array('content','require','内容不能为空！',1,'regex',3),
            );
            
            $model = M(); // 实例化User对象
            if ($model->validate($rules)->create()!==false){
                $data=I('post.');
                $result=sp_send_email($data['to'], $data['subject'], $data['content']);
                if($result && empty($result['error'])){
                    $this->success('发送成功！');
                }else{
                    $this->error('发送失败：'.$result['message']);
                }
            }else{
                $this->error($model->getError());
            }
            
        }else{
            $this->display();
        }
        
    }
}

