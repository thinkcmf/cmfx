<?php
/*
 * 功       能: API基础类
 * 作       者: 刘海艇
 * 修改日期: 2013-06-23
 */

class API
{
	static $instance;
	
	//初始化
	public static function init()
	{
		if(self::$instance == null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//初次进行校验
	public function check()
	{
		$echoStr = $_GET["echostr"];
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}
	
	//检验是否来之微信
	private function checkSignature()
	{
		global $_CFG;
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		
		$token = self::getToken();
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr,SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	//向微信服务器回复消息
	public function responseMsg()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//extract post data
		if (!empty($postStr)){
			global $postObj;
			global $_CFG;
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$type = trim($postObj->MsgType); //trim不能少！！
			//加载处理方法，分发
			include_once $_CFG['HANDLE']['message'][$type];
		}else {
			echo "";
			exit;
		}
	}
	
	//获取token
	private function getToken($weixin_ini=""){
		global $_CFG;
		include_once HANDLE_DIR.'db.class.php';
		include_once HANDLE_DIR.'tools.class.php';
		
		//实例化数据库类并连接数据库
		$db = new DB;
		$db->open();
		return $db->get_field('wx_config', "_key='WX_TOKEN'", '_value');
	}
	
	//获取appid和appkey
	private function getApp(){
		//实例化数据库类并连接数据库
		$db = new DB;
		$db->open();
		$rst['appid'] = $db->get_field('wx_config', "_key='WX_APPID'", '_value');
		$rst['appsec'] = $db->get_field('wx_config', "_key='WX_APPSECRET'", '_value');
		return $rst;
	}
	
	//获取access_token
	public function getAccessToken(){
		//实例化数据库类并连接数据库
		$db = new DB;
		$db->open();
		$accessToken = $db->get_field('wx_config', "_key='WX_ACCESS_TOKEN'", '_value');
		$access = $accessToken ? json_decode($access, true) : array();
		
		if($access['expires_in'] < time()){ //已经过期
			$app = self::getApp(); //获取appid和appsecret
			include_once HANDLE_DIR.'tools.class.php';
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app['appid'].'&secret='.$app['appsec'];
			$access = curl_get($url);
			$access_arr = json_decode($access, true);
			$access_arr['expires_in'] += time();
			$data = array(
					'_key' => 'WX_ACCESS_TOKEN',
					'_value' => json_encode($access_arr),
			);
			//保存
			if(empty($accessToken)){ //需要创建
				$db->insert('wx_config', $data);
			}else{ //需要更新
				$db->update('wx_config', $data, "_key='WX_ACCESS_TOKEN'");
			}
			return $access_arr['access_token'];
		}else{
			return $access['access_token'];
		}
	}
}

?>