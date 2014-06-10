<?php
/*
 * 功       能: 本地调试文件
 * 作       者: 刘海艇
 * 修改日期: 2013-06-24
 */
header('content-type:text/html;charset=utf-8');

include_once "api.class.php";
include_once "api.functions.php";
include_once 'debug.class.php';	

if(empty($_GET['type']))
{
	die('type不能为空，可选：text/image/location/event!');
}
$type = $_GET['type'];
$data = DEBUG::$type();

//推送地址
$http = 'http://';
if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) $http='https://';
$url = $http.$_SERVER['HTTP_HOST'].'/api/wx/api/boot.php?id=gh_ef34a6c9f774';
//消息推送
$html = pushXml($url, $data);

header('content-type:text/html;charset=UTF-8');
echo '<h2>获取数据</h2>
	  <pre style="background-color:#f6f6f6;border:1px solid #ddd;">'
	  .preg_replace('/\t/','',htmlspecialchars($data)).'</pre>';

echo '<h2>返回数据</h2>
	  <pre style="background-color:#f6f6f6;border:1px solid #ddd;">'
	  .preg_replace('/\t/','',htmlspecialchars($html)).'</pre>';

echo '<h2>加载的文件</h2>
	  <pre style="background-color:#f6f6f6;border:1px solid #ddd;">';
print_r(get_included_files());
echo '</pre>';