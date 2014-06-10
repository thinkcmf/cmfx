<?php
namespace Common\Action;
use Common\Action\HomeBaseAction;
class MemberbaseAction extends HomeBaseAction{
	
	function _initialize() {
		parent::_initialize();
		if(!isset($_SESSION["MEMBER_id"])){
			$this->error('您还没有登录！', U('Member/index/index'));
		}else if($_SESSION["MEMBER_status"] == 2 && C('SP_MEMBER_EMAIL_ACTIVE')=='true'){
			$this->error('您的账号需要激活才能使用', U('Member/index/disable'));
		}
		
		if(!C('THIRD_UDER_ACCESS') && $_SESSION["MEMBER_type"]!='local'){ //第三方用户拥有低于本地帐户的权限
			$this->error("您需要绑定本地账号才能获取更多权限！", U('Member/index/bang'));
		}else{
			$_SESSION["MEMBER_access"] = 'total';
			if(isset($_SESSION['before_login_url'])){ //登陆前已指定跳转地址
				$goUrl = $_SESSION['before_login_url'];
				unset($_SESSION['before_login_url']);
				$this->success("正在跳转...！", $goUrl);
			}
		}
	}
	
}