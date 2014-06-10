<?php

global $postObj;

include_once HANDLE_DIR.'db.class.php';
include_once HANDLE_DIR.'tools.class.php';

//实例化数据库类并连接数据库
$db = new DB;
$db->open();

/*-------------获取经纬度的省区-------------*/
$location_json = curl_get('http://api.map.baidu.com/geocoder/v2/?ak=E4805d16520de693a3fe707cdc962045&location='.$postObj->Location_X.','.$postObj->Location_Y.'&output=json&pois=1');
$location_arr = json_decode($location_json,true);
/*-------------获取省区结束----------------*/

//用户消息
$data1 = array(
	'from' => $postObj->FromUserName,
	'to'	=> $postObj->ToUserName,
	'location_x' => trim($postObj->Location_X),
	'location_y' => trim($postObj->Location_Y),
	'province'	=> $location_arr['result']['addressComponent']['province'],
	'scale' => trim($postObj->Scale),
	'label' => $postObj->Label,
	'time'=> $postObj->CreateTime,
	'message_id' => $postObj->MsgId,
);
//消息存入数据库
$db->insert('wx_message_location', $data1);
//获取回复消息模板
$textTpl = Tools::$Text;

if(!empty( $data1['location_x'] ))
{
	$msgType = "text";
	$contentStr = "您的位置已经收到!";
	$resultStr = sprintf($textTpl, $data1['from'], $data1['to'], $data1['time'], $msgType, $contentStr);
	echo $resultStr;
}