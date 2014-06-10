<?php
/**
 * 参    数：
 * 作    者：lht
 * 功    能：结合ThinkSDK完成腾讯,新浪微博,人人等用户的第三方登录
 * 修改日期：2013-12-11
 */
namespace Api\Action;
use Think\Action;
class OauthAction extends Action {
	
	function _initialize() {}
	
	//登录地址
	public function login($type = null){
		empty($type) && $this->error('参数错误');
		//加载ThinkOauth类并实例化一个对象
		import("ThinkSDK");
		$sns  = ThinkOauth::getInstance($type);
		//跳转到授权页面
		redirect($sns->getRequestCodeURL());
	}
	
	//授权回调地址
	public function callback($type = null, $code = null){
		header('content-type:text/html;charset=UTF-8;');
		(empty($type) || empty($code)) && $this->error('参数错误');
	
		//加载ThinkOauth类并实例化一个对象
		import("ThinkSDK");
		$sns  = ThinkOauth::getInstance($type);
	
		//腾讯微博需传递的额外参数
		$extend = null;
		if($type == 'tencent'){
			$extend = array('openid' => I("get.openid"), 'openkey' => I("get.openkey"));
		}
	
		//请妥善保管这里获取到的Token信息，方便以后API调用
		//调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
		//如： $qq = ThinkOauth::getInstance('qq', $token);
		$token = $sns->getAccessToken($code , $extend);
		//获取当前登录用户信息
		if(is_array($token)){
			$user_info = A('Type', 'Event')->$type($token);
			if($_SESSION["MEMBER_type"] == 'local'){
				self::_bang_handle($user_info, $type, $token);
			}else{
				self::_login_handle($user_info, $type, $token);
			}
		}else{
			$this->success('登录失败！',U("Portal/index/index"));
		}
	}
	
	//绑定第三方账号
	private function _bang_handle($user_info, $type, $token){
		$OMember = M('OauthMember');
		//账户是否已经存在
		$rst = $OMember->where("_from='{$type}' and openid='{$token['openid']}' and status=1")->find();
		if(!empty($rst)){
			$lock_to_id=$rst['lock_to_id'];
			$members_model=M("Members");
			$member=$members_model->field("user_pass")->where("ID='$lock_to_id'")->find();
			
			if(empty($member['user_pass'])){
				//$where['_from']=$type;
				//$where['openid']=$token['openid'];
				$result=$OMember->where("_from='{$type}' and openid='{$token['openid']}'")->save(array("lock_to_id"=>$_SESSION["MEMBER_id"]));
				if($result){
					$members_model->where("ID='$lock_to_id'")->delete();
					$this->success('绑定成功！',U("Member/center/index"));
				}else{
					echo $OMember->getLastSql();
					//$this->error('账号绑定失败！',U("Member/center/index"));
				}
			}else{
				$this->error('该帐号已被本站其他账号绑定！',U("Member/center/index"));
			}
			
		}
		/* $data = array(
				'_from' => $type,
				'_name' => $user_info['name'],
				'head_img' => $user_info['head'],
				'create_time' => time(),
				'lock_to_id' => $_SESSION["MEMBER_id"],
				'last_login_time' => time(),
				'last_login_ip' => get_client_ip(),
				'login_times' => 1,
				'status' => 1,
				'access_token' => $token['access_token'],
				'expires_date' => (int)(time()+$token['expires_in']),
				'openid' => $token['openid'],
		);
		if($OMember->add($data)){
			$this->success('账号绑定成功！',U("Member/center/index"));
		}else{
			$this->error('账号绑定失败！',U("Member/center/index"));
		} */
	}
	
	//登陆
	private function _login_handle($user_info, $type, $token){
		$OMember = M('OauthMember');
		$rst = $OMember->where("_from='{$type}' and openid='{$token['openid']}'")->find();
		$return = array(0);
		$local_username="";
		if($rst){
			$rst2 = M('Members')->where('ID='.$rst['lock_to_id'])->find();
			if($rst2){
				if($rst2['user_status'] == '0' || $rst['user_status'] == '0')
					$return = array(0,'您可能已经被列入黑名单，请联系网站管理员！');
				else{
					$type = 'local';
					$_SESSION["MEMBER_status"] = $rst2['user_status'];
					$return = array($rst['lock_to_id']);
					$local_username=strpos($rst2['user_login_name'],"游客")?"":$rst2['user_login_name'];
				}
			}else{
				//数据库已经有该用户登录信息
				$data = array(
						'last_login_time' => time(),
						'last_login_ip' => get_client_ip(),
						'login_times' => $rst['login_times']+1,
						'access_token' => $token['access_token'],
						'expires_date' => (int)(time()+$token['expires_in']),
				);
				if($OMember->where("_from='{$type}' and openid='{$token['openid']}'")->save($data))
				$return[0] = $rst['lock_to_id'];
			}
		}else{
			//本地用户中创建对应一条数据
			$mem_insert = array(
					'user_login_name' => $type.'游客',
					'user_pic_assetid' => $user_info['head'],
					'last_login_time' => time(),
					'last_login_ip' => get_client_ip(),
					'create_time' => time(),
					'user_status' => '1',
			);
			$id = M("Members")->add($mem_insert);
			//第三方用户表中创建数据
			$data = array(
					'_from' => $type,
					'_name' => $user_info['name'],
					'head_img' => $user_info['head'],
					'create_time' => time(),
					'lock_to_id' => $id,
					'last_login_time' => time(),
					'last_login_ip' => get_client_ip(),
					'login_times' => 1,
					'status' => 1,
					'access_token' => $token['access_token'],
					'expires_date' => (int)(time()+$token['expires_in']),
					'openid' => $token['openid'],
			);
			$OMember->add($data) && ($return = array($id));
		}
		if($return[0]){
			$_SESSION["MEMBER_type"] = $type;
			$_SESSION["MEMBER_id"] = $return[0];
			$_SESSION['MEMBER_name'] = empty($local_username)?$user_info['name']:$local_username;
			if(!isset($return[1])) $return[1] = '登陆成功！';
			$this->success($return[1], U("Member/center/index"));
		}else{
			if(!isset($return[1])) $return[1] = '登陆失败';
			$this->error($return[1],U("Portal/index/index"));
		}
	}
}