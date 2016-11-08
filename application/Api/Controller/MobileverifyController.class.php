<?php
/**
 * 手机验证码发送
 */
namespace Api\Controller;

use Think\Controller;

class MobileverifyController extends Controller{

    // 手机验证码发送
    public function send(){
        if(IS_POST){
            $mobile=I('post.mobile/s');
            $result= hook_one("send_mobile_verify_code",array('mobile'=>$mobile));
            /*
             *-1:发送次数过多，不能再发送
             *-2:短信服务商短信接口发送失败 
             */
            if($result['error']===0){
                $this->success('验证码已发送到您手机，请查收！');
            }else{
                $this->error($result['error_msg']);
            }
        }
    }
}

