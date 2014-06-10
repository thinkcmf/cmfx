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
	'content' => trim($postObj->Content),
	'time'=> $postObj->CreateTime,
	'message_id' => $postObj->MsgId,
);
//消息存入数据库
$db->insert('wx_message_text', $data1);

//获取默认回复
$default_key = 'ANSWER_DEFAULT_'.round(rand(1,4));
$contentStr = $db->get_field("wx_config","_key='$default_key'","_value");

if(mb_substr($data1['content'], 0, 2, 'utf-8')=='天气'){ //天气查询
	$contentStr = getWeather($data1['content'], false);
}else if(mb_substr($data1['content'], 0, 2, 'utf-8')=='快递'){ //快递查询
	$contentStr = doGetKuaiDi($data1['content']);
}else if($db->count('wx_answer', "_key='{$data1['content']}'")>0){
	$contentStr = $db->get_field("wx_answer", "_key='{$data1['content']}'", "_value");
}else{//机器人查询
	$str = $data1['content'];
	$sql = "CONCAT(key1,key2,key3)<>'' and INSTR('$str',key1) and INSTR('$str',key2) and INSTR('$str',key3)  
			or (CONCAT(key1,key2,key3)='' and question='$str')";
	$answerStrThird =  $db->get_field("wx_answer_robot", $sql, "answer");
	if( $answerStrThird ){
		$contentStr = $answerStrThird;
	}
}

/*
 * 作用：获取图文回复
 * 参数：$term_id, int, 选择的图文封面term_id
 */
function getTwData($db, $term_id){
	global $_CFG;
	$fields = 'select title,description,img,linkurl from '.$_CFG["DB"]['DB_PREFIX'].'wx_tw';
	$where = " where term_id = $term_id order by create_time desc";
	$tablePre = $_CFG["DB"]['DB_PREFIX'].'';
	$termData = $db->select($db->query($fields.'_terms'.$where));
	$itemsData = $db->select($db->query($fields.$where));
	$arr = array_merge($termData, $itemsData);
	//修正图片地址
	$http = 'http://';
	if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) $http='https://';
	$url = $http.$_SERVER['HTTP_HOST'].'/data/upload/';
	foreach($arr as $k=>$v){
		$arr[$k]['img'] = $url.$v['img'];
	}
	return $arr;
}

//是否需要图文回复
if(preg_match('/^#tw([0-9]+)#$/i', $contentStr, $matches)){
	$twData = getTwData($db, $matches[1]);
	exit(Tools::answer_tuwen($postObj->ToUserName, $data1['from'], $twData));
}

exit(Tools::answer_text($postObj->ToUserName, $data1['from'], $contentStr));

