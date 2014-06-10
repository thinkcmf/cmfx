<?php

/**
 */
namespace Admin\Action;
use Common\Action\AdminbaseAction;
class PublicAction extends AdminbaseAction {

    function _initialize() {}
    
    //后台登陆界面
    public function login() {
    	if(isset($_SESSION['ADMIN_ID'])){//已经登录
    		$this->success("已登录！",U("Index/index"));
    	}else{
    		$this->display();
    	}
    }
    
    public function logout(){
    	session('[destroy]'); 
    	$this->redirect("public/login");
    }
    
    public function dologin(){
    	$name = I("post.username");
    	if(empty($name)){
    		$this->error("用户名或邮箱不能为空！");
    	}
    	$pass = I("post.password");
    	if(empty($pass)){
    		$this->error("密码不能为空！");
    	}
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error("验证码不能为空！");
    	}
    	//验证码
    	if($_SESSION['_verify_']['verify']!=strtolower($verrify))
    	{
    		$this->error("验证码错误！");
    	}else{
    		$user = D("Users");
    		if(strpos($name,"@")>0){//邮箱登陆
    			$where['user_email']=$name;
    		}else{
    			$where['user_login']=$name;
    		}
    		
    		$result = $user->where($where)->find();
    		if($result != null)
    		{
    			if($result['user_pass'] == sp_password($pass))
    			{
    				//登入成功页面跳转
    				$_SESSION["ADMIN_ID"]=$result["ID"];
    				$_SESSION['name']=$result["user_login"];
    				session("roleid",$result['role_id']);
    				$result['last_login_ip']=get_client_ip();
    				$result['last_login_time']=date("Y-m-d H:i:s");
    				$user->save($result);
    				setcookie("admin_username",$name,time()+30*24*3600,"/");
    				$this->success("登录验证成功！",U("Index/index"));
    			}else{
    				$this->error("密码错误！");
    			}
    		}else{
    			$this->error("用户名不存在！");
    		}
    	}
    }

}

?>
