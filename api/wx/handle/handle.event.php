<?php
global $postObj;

include_once HANDLE_DIR.'db.class.php';
include_once HANDLE_DIR.'tools.class.php';
//实例化数据库类并连接数据库
$db = new DB;
$db->open();
//用户表
$table = 'wx_user';

if(trim($postObj->Event) == 'subscribe'){
	$access_token = O('API')->getAccessToken();
	$data = array(
		'openid' => $postObj->FromUserName,
		'subscribe_time' => $postObj->CreateTime,
		'belong'	=> $_GET['id'],
	);
	//获取用户详情
	if(!empty($access_token)){
		$userInfo = userInfo($postObj->FromUserName, $access_token);
		$data['nickname'] = $userInfo['nickname'];
		$data['sex'] = $userInfo['sex'];
		$data['language'] = $userInfo['language'];
		$data['city'] = $userInfo['city'];
		$data['province'] = $userInfo['province'];
		$data['country'] = $userInfo['country'];
		$data['headimgurl'] = $userInfo['headimgurl'];
	}
	//保存用户信息
	$db->insert($table, $data);
	//欢迎词
	$contentStr = $db->get_field('wx_config',"_key='ANSWER_WELCOME' and belong='{$_GET['id']}'",'_value');
	exit(Tools::answer_text($postObj->ToUserName, $postObj->FromUserName, $contentStr));
}else if(trim($postObj->Event) == 'unsubscribe'){
	$data = array(
		'unsubscribe_time'=> $postObj->CreateTime,
		'status' => 0,
	);
	//注销用户
	$db->update($table, $data, "openid='{$postObj->FromUserName}' and status=1 and belong='{$_GET['id']}'");
}