<?php
/*
 * 功       能: 微信公众平台凭证
 * 作       者: 刘海艇
 * 修改日期: 2013-06-22
 */
if(function_exists('saeAutoLoader') ){
	$db=array(
	    'DB_TYPE' => 'mysql',
		'DB_DEPLOY_TYPE'=> 1,
		'DB_RW_SEPARATE'=>true,
	    'DB_HOST' => SAE_MYSQL_HOST_M,
	    'DB_NAME' => SAE_MYSQL_DB,
	    'DB_USER' => SAE_MYSQL_USER,
	    'DB_PWD' => SAE_MYSQL_PASS,
	    'DB_PORT' => SAE_MYSQL_PORT,
		'DB_PREFIX' => 'sp_',
	);
}else{
	$db = require '../../../conf/db.php';
}
//基本配置
$_CFG = array
(
	'DB' => $db, //数据库配置
	'PATH'=> array
	(
		'ROOT'  => dirname(__FILE__), //API根目录
		//'access_token' => 'access_token', //access_token保存文件
	),
);

//网站根目录
defined('WX_ROOT') or define('WX_ROOT',$_SERVER['DOCUMENT_ROOT'].'/api/wx');
//消息处理程序目录
defined('HANDLE_DIR') or define('HANDLE_DIR', WX_ROOT.'/handle/');
//消息处理程序
$_CFG['HANDLE'] = array
(
	'message' => array //消息推送
	(
		'text' => HANDLE_DIR.'handle.text.php',
		'image' => HANDLE_DIR.'handle.image.php',
		'location' => HANDLE_DIR.'handle.location.php',
		'link' => HANDLE_DIR.'handle.link.php',
		'voice' => HANDLE_DIR.'handle.voice.php',
		'event' => HANDLE_DIR.'handle.event.php',
	),
	'modules' => array( //模块加载
		'weather' => HANDLE_DIR.'modules/weather/index.php',
		'kuaidi' => HANDLE_DIR.'modules/kuaidi/index.php',
		'splitwords' => HANDLE_DIR.'modules/splitwords/index.php',
	),
);

foreach($_CFG['HANDLE']['modules'] as $k=>$v){
	include_once $v;
}

?>