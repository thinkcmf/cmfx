<?php
/*
 * 摘	要: 中文分词
 * 作       者: 刘海艇
 * 修改日期: 2013-06-27
 */
function splitWords($str, $style='array'){
	//字典文件太大，防止读取时服务器配置内存不够用
	//@ini_set("memory_limit","30M");
	
	require_once dirname(__FILE__)."/utf8_splitword.php";
	
	$sp = new SplitWord();
	$result = $sp->SplitRMM($str);
	$sp->Clear();
	if($style=='string'){
		return $result;
	}else if($style=='array'){
		return split(',',$result);
	}
}