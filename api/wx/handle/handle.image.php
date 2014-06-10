<?php

global $postObj;

include_once HANDLE_DIR.'db.class.php';
include_once HANDLE_DIR.'tools.class.php';

//实例化数据库类并连接数据库
$db = new DB;
$db->open();

//用户消息
$data1 = array(
	'from' => $postObj->FromUserName,
	'to'	=> $postObj->ToUserName,
	'url' => trim($postObj->PicUrl),
	'time'=> $postObj->CreateTime,
	'message_id' => $postObj->MsgId,
);
//消息存入数据库
$db->insert('wx_message_image', $data1);
//获取回复消息模板
$textTpl = Tools::$Text;

if(!empty( $data1['url'] ))
{
	$msgType = "text";
	$contentStr = "您的图片已经收到!";
	$resultStr = sprintf($textTpl, $data1['from'], $data1['to'], $data1['time'], $msgType, $contentStr);
	echo $resultStr;
}else{
	echo "Input something...";
}