<?php
/*
 * 摘	要: 快递查询
 * 注	释：$name, string, 快递名称如申通
 * 		    $num, string, 快递单号
 * 作	者: 刘海艇
 * 修改日期: 2013-07-09
 */
include_once dirname(__FILE__).'/config.php';

function getKuaiDi($ID, $name){
	global $kuaidi;
	include_once HANDLE_DIR.'libs/pinYin.class.php';
	include_once dirname(dirname(dirname(__FILE__))).'/tools.class.php';
	$name = pinYin::convert($name, 'utf8');
	/*-------------- 获取内容 -------------*/
	$url = $kuaidi['url'].'?key='.$kuaidi['key'].'&order='.$ID.'&id='.$name.'&show=xml';
	$response = curl_get($url);
	/*---------------- 结束获取----------------*/
	$kuaidiobj = simplexml_load_string($response); //xml解析
	$kuaidistatus = $kuaidiobj->Status; //获取快递状态
	$kuaistr = strval($kuaidistatus); //对象转换为字符串
	$contentStr0 = $kuaidi['statusCode'][$kuaistr]; //根据数组返回
	foreach ($kuaidiobj->Data->Order as $a)
	{
		foreach ($a->Time as $b)
		{
			foreach ($a->Content as $c)
			{$m .= "$b\n{$c}。\n";}
		}
	}
	//遍历获取快递时间和事件
	$contentStr="你的快递单号{$ID}{$contentStr0}\n{$m}";
	return $contentStr;
}

function doGetKuaiDi($str){
	//$str = ltrim($str, '快递');
	$str = mb_substr($str.'1',2,-1,'utf-8');
	preg_match('/[0-9]+/',$str, $arr);
	$arr[1] = str_replace($arr[0], '', $str);
	return getKuaiDi($arr[0], $arr[1]);
}