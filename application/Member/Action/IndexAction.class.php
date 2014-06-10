<?php

/**
 * 会员注册登录
 */
namespace Member\Action;
use Common\Action\HomeBaseAction;
class IndexAction extends HomeBaseAction {
    //登录
	public function index() {
		if(isset($_SESSION["MEMBER_id"])){
			redirect(U("Member/center/index"));
		}else{
			if(empty($_SESSION['login_http_referer'])){
				$_SESSION['login_http_referer']=$_SERVER["HTTP_REFERER"];
			}
			
			$this->display(":login");
		}

    }
	
	function test(){
		include UC_CLIENT_ROOT."client.php";
		$uc_uid2=uc_user_register("1test", "666666", 'test11@126.com');
	}

    //登录验证
    function dologin(){
    	extract($_POST);

    	if($_SESSION['_verify_']['verify']!=strtolower($verify))
    	{
    		$this->error("验证码错误！");
    	}else{
    		$where['user_login_name']=$name;
    		$result = M('Members')->where($where)->find();
    		$ucenter_syn=C("UCENTER_ENABLED");
			
			$ucenter_old_user_login=false;

    		$ucenter_login_ok=false;
    		if($ucenter_syn){
    			setcookie("thinkcmf_auth","");
    			include UC_CLIENT_ROOT."client.php";
    			list($uc_uid, $username, $password, $email)=uc_user_login($name, $passwd);
				
    			if($uc_uid>0){
    				if(!$result){
    					$data=array(
    							'user_login_name' => $name,
    							'user_email' => $email,
    							'user_pass' => sp_password($passwd),
    							'last_login_ip' => get_client_ip(),
    							'create_time' => time(),
    							'last_login_time' => time(),
    							'user_status' => '1',
    					);
    					$id=M('Members')->add($data);
    					$data['ID']=$id;
    					$result=$data;
    				}
					
    			}else{
				
    				switch ($uc_uid){
    					case "-1"://用户不存在，或者被删除
    						if($result){//本应用已经有这个用户
    							if($result['user_pass'] == sp_password($passwd)){//本应用已经有这个用户,且密码正确，同步用户
    								$uc_uid2=uc_user_register($name, $passwd, $result['user_email']);
    								if($uc_uid2<0){
										$uc_register_errors=array(
											"-1"=>"用户名不合法",
											"-2"=>"包含不允许注册的词语",
											"-3"=>"用户名已经存在",
											"-4"=>"Email格式有误",
											"-5"=>"Email不允许注册",
											"-6"=>"该Email已经被注册",
										);
    									$this->error("同步用户失败--".$uc_register_errors[$uc_uid2]);
										
										
    								}
    								$uc_uid=$uc_uid2;
    							}else{
    								$this->error("密码错误！");
    							}
    						}
							
    						break;
    					case -2://密码错
    						if($result){//本应用已经有这个用户
    							if($result['user_pass'] == sp_password($passwd)){//本应用已经有这个用户,且密码正确，同步用户
    								$uc_user_edit_status=uc_user_edit($name,"",$passwd,"",1);
    								if($uc_user_edit_status<=0){
    									$this->error("登陆错误！");
    								}
    								list($uc_uid2)=uc_get_user($name);
    								$uc_uid=$uc_uid2;
									$ucenter_old_user_login=true;
    							}else{
    								$this->error("密码错误！");
    							}
    						}else{
    							$this->error("密码错误！");
    						}
    						
    						break;

    				}
    			}
    			$ucenter_login_ok=true;
    			echo uc_user_synlogin($uc_uid);
    		}
			//exit();
    		if($result != null)
    		{
    			if($result['user_pass'] == sp_password($passwd)|| $ucenter_login_ok)
    			{
    				$_SESSION["MEMBER_status"]=$result["user_status"];
    				$_SESSION["MEMBER_type"]='local';
    				$_SESSION["MEMBER_id"]=$result["ID"];
    				$_SESSION['MEMBER_name']=$result["user_login_name"];
    				session("roleid", $result['role_id']);
    				//写入此次登录信息
    				$data = array(
    						'last_login_time' => time(),
    						'last_login_ip' => get_client_ip(),
    				);
    				M('Members')->where("ID=".$result["ID"])->save($data);
					$redirect=empty($_SESSION['login_http_referer'])?U("Member/center/index"):$_SESSION['login_http_referer'];
					$_SESSION['login_http_referer']="";
					$ucenter_old_user_login_msg="";
					
					if($ucenter_old_user_login){
						$ucenter_old_user_login_msg="老用户请在跳转后在社区再次登陆";
					}
					
					if(strpos($redirect,"bbs.thinkcmf.com")){
						$redirect="http://bbs.thinkcmf.com/member.php?mod=logging&action=login";
					}
    				$this->success("登录验证成功！".$ucenter_old_user_login_msg, $redirect);
    			}else{
    				$this->error("密码错误！");
    			}
    		}else{
    			$this->error("用户名不存在！");
    		}
    	}
    }

    //注册
    public function register(){
    	$this->display(":register");
    }

    //注册验证
    function doregister(){
    	extract($_POST);
    	//用户名需过滤的字符的正则
    	$stripChar = '?<*.>\'';
    	if($_SESSION['_verify_']['verify']!=strtolower($verify))
    	{
    		$this->error("验证码错误！");
    	}else if(preg_match('/['.$stripChar.']/is', $name)==1){
    		$this->error('用户名中包含'.$stripChar.'等非法字符！');
    	}else if($pass!=$repass){
    		$this->error("两次密码输入不一致！");
    	}else if(strlen($pass) < 5 || strlen($pass) > 12){
    		$this->error("密码长度至少5位，最多12位！");
    	}else  if (ereg("/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i;",$email)){
    		$this->error("邮箱格式不正确！");
    	}else{
    		$where['user_login_name']=$name;
    		$where['user_email']=$email;
    		$where['_logic'] = 'OR';
    		$ucenter_syn=C("UCENTER_ENABLED");
    		$uc_checkemail=1;
    		$uc_checkusername=1;
    		if($ucenter_syn){
    			include UC_CLIENT_ROOT."client.php";
    			$uc_checkemail=uc_user_checkemail($email);
    			$uc_checkusername=uc_user_checkname($name);
    		}
    		$result = M('Members')->where($where)->count();
    		if($result || $uc_checkemail<0 || $uc_checkusername<0){
    			$this->error("用户名或者该邮箱已经存在！");
    		}else{
    			$uc_register=true;
    			if($ucenter_syn){
				
    				$uc_uid=uc_user_register($name,$pass,$email);
					//exit($uc_uid);
    				if($uc_uid<0){
    					$uc_register=false;
    				}
    			}
    			if($uc_register){
    				$data=array(
    						'user_login_name' => $name,
    						'user_email' => $email,
    						'user_pass' => sp_password($pass),
    						'last_login_ip' => get_client_ip(),
    						'create_time' => time(),
    						'last_login_time' => time(),
    						'user_status' => '2',
    				);
    				$rst = M('Members')->add($data);
    				//登入成功页面跳转
    				$_SESSION["MEMBER_type"]='local';
    				$_SESSION["MEMBER_id"]=$rst;
    				$_SESSION['MEMBER_name']=$name;
    				$_SESSION['MEMBER_status']='2';

    				//发送激活邮件
    				self::_send_to_active();
    				$this->success("注册成功！",U("Member/center/index"));
    			}else{
    				$this->error("注册失败！",U("Member/index/register"));
    			}

    		}
    	}
    }

    //绑定本地账号
    function bang(){
    	$this->display(":bang");
    }

    //提交 绑定本地账号
    function dobang(){
    	extract($_POST);
    	//用户名需过滤的字符的正则
    	$stripChar = '?<*.>\'\"';
    	if(!isset($_SESSION["MEMBER_id"]))
    	{
    		$this->error("登录后才能绑定本地帐户！");
    	}else if($pass!=$repass){
    		$this->error("两次密码输入不一致！");
    	}else if(preg_match('/['.$stripChar.']/is', $name)==1){
    		$this->error('用户名中包含'.$stripChar.'等非法字符！');
    	}else if(strlen($pass) < 5 || strlen($pass) > 12){
    		$this->error("密码长度至少5位，最多12位！");
    	}else{
    		$where['user_login_name']=$name;
    		$where['user_email']=$email;
    		$where['_logic'] = 'OR';
    		$result = M('Members')->where($where)->count();
    		if($result){
    			$this->error("用户名或者该邮箱已经存在！");
    		}else{
    			$data=array(
    					'user_login_name' => $name,
    					'user_email' => $email,
    					'user_pass' => sp_password($pass),
    					'last_login_ip' => get_client_ip(),
    					'create_time' => time(),
    					'last_login_time' => time(),
    					'user_status' => '1',
    			);
    			if(M('Members')->where('ID='.$_SESSION["MEMBER_id"])->save($data)){
    				$_SESSION["MEMBER_type"] = 'local';
    				$this->success("绑定本地帐户成功！", U("Member/center/index"));
    			}else
    				$this->error("绑定本地帐户失败！");
    		}
    	}
    }

    //退出
    public function logout(){
    	$ucenter_syn=C("UCENTER_ENABLED");
    	$login_success=false;
    	if($ucenter_syn){
    		include UC_CLIENT_ROOT."client.php";
    		echo uc_user_synlogout();
    	}
    	session_destroy();
    	$this->success("退出成功！", U(C("DEFAULT_GROUP")."/index/index"));
    }
	
	public function logout2(){
    	$ucenter_syn=C("UCENTER_ENABLED");
    	$login_success=false;
    	if($ucenter_syn){
    		include UC_CLIENT_ROOT."client.php";
    		echo uc_user_synlogout();
    	}
		if(isset($_SESSION["MEMBER_id"])){
		$referer=$_SERVER["HTTP_REFERER"];
			$_SESSION=array();
			$_SESSION['login_http_referer']=$referer;
			$this->success("退出成功！",U("Member/index/index"));
		}else{
			redirect(U("Member/index/index"));
		}
    	
		
		

    }

    //修改密码
    function changepass(){
    	if (IS_POST) {
    		if($_POST['pass'] != $_POST['repass']){
    			$this->error("两次密码输入不一致！");
    			exit();
    		}
    		if(strlen($_POST['pass']) < 5 || strlen($_POST['pass']) > 12){
    			$this->error("密码长度至少5位，最多12位！");
    			exit();
    		}
    		$mem = M('Members');
    		$uid = $_SESSION["MEMBER_id"];

    		$user_info=$mem->where("ID=$uid")->find();
    		$old_password=$_POST['inipass'];
    		$password=$_POST['pass'];
    		if(sp_password($old_password)==$user_info['user_pass']){
    			if($user_info['user_pass']==sp_password($password)){
    				$this->error("新密码不能和原密码相同！");
    			}else{
    				$ucenter_syn=C("UCENTER_ENABLED");
    				$can_change_password=true;
    				if($ucenter_syn){
    					include UC_CLIENT_ROOT."client.php";
    					$uc_result=uc_user_edit($user_info['user_login_name'], $old_password, $password, "");
    					if(!$uc_result){
    						$can_change_password=false;
    					}
    				}
    				if($can_change_password){
    					$data['user_pass']=sp_password($password);
    					$data['ID']=$uid;
    					$r=$mem->save($data);
    					if ($r!=false) {
    						$this->success("修改成功！");
    					} else {
    						$this->error("修改失败！");
    					}
    				}else{
    					$this->error("修改失败！");
    				}

    			}
    		}else{
    			$this->error("原密码不正确！");
    		}
    	} else {
    		$this->error('提交数据为空！');
    	}
    }

    //账号激活页
    function disable(){
    	if(!isset($_SESSION["MEMBER_id"])){
    		$this->error('您还没有登录', U('Member/index/index'));
    	}else if($_SESSION["MEMBER_status"] != 2){
    		$this->error('您的账号不需要激活');
    	}
    	if($_GET['control'] == 'sendmail'){
    		$rst = self::_send_to_active();
    		if($rst){
    			$this->ajaxReturn(1, '邮件发送成功！', 1);
    		}else{
    			$this->ajaxReturn(2, '邮件发送失败！', 0);
    		}
    	}else{
    		$addr = M('Members')->where('ID='.$_SESSION["MEMBER_id"])->getField('user_email');
    		$addr_arr = split('@', $addr);
    		//注册邮箱的登录地址
    		$mail_login_addr = 'http://mail.'.$addr_arr[1];
    		$this->assign('goto', $mail_login_addr);
    		$this->display(":disable");
    	}
    }

    //发送邮件
    private function _send_to_active(){
    	$option = M('Options')->where(array('option_name'=>'member_email_active'))->find();
    	if(!$option){
    		$this->error('网站未配置账号激活信息，请联系网站管理员');
    	}
    	$options = json_decode($option['option_value'], true);
    	//邮件标题
    	$title = $options['title'];
    	$rst = M('Members')->where("ID=".$_SESSION['MEMBER_id'])->find();
    	$data = array(
    			'uid' => $_SESSION['MEMBER_id'],
    			'type'=> 'active',
    	);
    	$data['hash'] = sha1($data['uid'].$rst['create_time']);
    	//生成激活链接
    	$url = U('Member/Index/active',$data, true, false, true);
    	//邮件内容
    	$template = $options['template'];
    	$content = str_replace(array('http://#link#','#username#'), array($url,$rst['user_login_name']),$template);
    	return SendMail($rst['user_email'], $title, $content);
    }

    //账号激活处理
    function active(){
    	$uid = intval($_GET['uid']);
    	$hash = $_GET['hash'];
    	$line = M('Members')->where("ID=".$uid)->find();
    	if(sha1($uid.$line['create_time']) != $hash){
    		$this->error('激活链接无效！');
    	}
    	if($line['user_status']==1){
    		$this->error('您的账户不需要激活！');
    	}else if($line['user_status']==2){
    		$rst = M('Members')->where("ID=".$uid)->setField('user_status',1);
    		if($rst){
    			$_SESSION['MEMBER_status']='1';
    			$this->success('账号激活成功', U("Member/center/index"));
    		}else{
    			$this->error('未知错误，请联系网站管理员！');
    		}
    	}else{
    		$this->error('账户不存在！');
    	}
    }
}
?>
