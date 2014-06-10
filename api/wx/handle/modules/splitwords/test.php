<?php
/*
 * 摘	要: 分词工具使用演示
 * 注       释：本工具中文分词速度略慢，但是使用方便
 * 作       者: 刘海艇
 * 修改日期: 2013-06-27
 */

require_once "index.php";

header("content-type:text/html;charset=utf-8");
$str = "你叫什么名字啊，哈哈";

$start_time = microtime(true);
//---------------------------------
print_r(splitWords($str));
//------------------------------------
$end_time = microtime(true);
echo '耗时'.round($end_time-$start_time,3).'秒';