<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\Mobileverify;//Demo插件英文名，改成你的插件英文就行了
use Common\Lib\Plugin;

/**
 * Mobileverify
 */
class MobileverifyPlugin extends Plugin{//Demo插件英文名，改成你的插件英文就行了

        public $info = array(
            'name'=>'Mobileverify',//Demo插件英文名，改成你的插件英文就行了
            'title'=>'手机验证码',
            'description'=>'手机验证码',
            'status'=>1,
            'author'=>'ThinkCMF',
            'version'=>'1.0'
        );
        
        public $has_admin=1;//插件是否有后台管理界面

        public function install(){//安装方法必须实现
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
            return true;//卸载成功返回true，失败false
        }
        
        //实现的footer钩子方法
        public function send_mobile_verify_code($param){
            $to=$param['mobile'];
            $config=$this->getConfig();
            $expire_minute=intval($config['expire_minute']);
            $expire_minute=empty($expire_minute)?30:$expire_minute;
            $expire_time=time()+$expire_minute*60;
            $code=sp_get_mobile_code($param['mobile'],$expire_time);
            $result=false;
            //....send message
            if($code!==false){
                import("CCPRestSmsSDK",'./plugins/Mobileverify/Lib',".php");
                $datas=array($code,$expire_minute);
                $tempId=$config['template_id'];
                //主帐号,对应开官网发者主账号下的 ACCOUNT SID
                $accountSid= $config['account_sid'];
                //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
                $accountToken= $config['auth_token'];
                //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
                //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
                $appId=$config['app_id'];
                //请求地址
                //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
                //生产环境（用户应用上线使用）：app.cloopen.com
                $serverIP='app.cloopen.com';
                //请求端口，生产环境和沙盒环境一致
                $serverPort='8883';
                
                //REST版本号，在官网文档REST介绍中获得。
                $softVersion='2013-12-26';
                
                $rest = new \YunTongXunREST($serverIP,$serverPort,$softVersion);
                $rest->setAccount($accountSid,$accountToken);
                $rest->setAppId($appId);
                
                // 发送模板短信
                $reponse = $rest->sendTemplateSMS($to,$datas,$tempId);
                $reponse = json_decode(json_encode($reponse),true);
                if(empty($reponse)) {
                    $result = array(
                        'error'=>1,
                        'error_msg'=>'云通讯返回结果错误'
                    );
                }else{
                    if($reponse['statusCode']!=0) {
                        $result = array(
                            'error'=>1,
                            'error_msg'=>$reponse['statusMsg']
                        );
                    }else{
                        $result = array(
                            'error'=>0,
                            'error_msg'=>'发送成功！'
                        );
                    }
                }
                
                
            }else{
                $result = array(
                    'error'=>1,
                    'error_msg'=>'发送次数过多，不能再发送'
                );
            }
            
            if($result['error']===0){
                sp_mobile_code_log($to, $code,$expire_time);
            }
        	return $result;
        }

    }