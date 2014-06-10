<?php
/*
 * 功       能: API启动文件，将调用所需API
 * 作       者: 刘海艇
 * 修改日期: 2013-06-22
 */

error_reporting(E_ERROR);

include_once "api.config.php";
include_once "api.class.php";
include_once "api.functions.php";
include_once 'api.returnCode.php';

$api = API::init();
//token验证
if(!empty($_GET["echostr"])) $api->check();
//消息回复
$api->responseMsg();