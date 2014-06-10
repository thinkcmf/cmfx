<?php
/*
 * 摘	要: 天气查询工具使用演示
 * 作       者: 刘海艇
 * 修改日期: 2013-06-27
 */

require_once "index.php";

header("content-type:text/html;charset=utf8");

$start_time = microtime(true);
//---------------------------------
print_r(getWeather('上海市'));
//------------------------------------
$end_time = microtime(true);
echo '<br><br>耗时'.round($end_time-$start_time,3).'秒';