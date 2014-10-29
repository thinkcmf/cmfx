<?php

/**
 * 别名定义
 */
return array(
	    //邮件
	    'PHPMailer' => SPAPP_PATH.'Lib/Util/class.phpmailer.php',
	    //Pclzip
	    'Pclzip' => SPAPP_PATH.'Lib/Util/Pclzip.class.php',
	    //UploadFile
	    "UploadFile" => SPAPP_PATH.'Lib/Util/UploadFile.class.php',
		"CloudUploadFile" => SPAPP_PATH.'Lib/Util/CloudUploadFile.class.php',
	    //文件操作类 Dir
	    "Dir" => SPAPP_PATH.'Lib/Util/Dir.class.php',
	    //树
	    "Tree" => SPAPP_PATH.'Lib/Util/Tree.class.php',
			//树
		"PathTree" => SPAPP_PATH.'Lib/Util/PathTree.class.php',
		"Input" => SPAPP_PATH.'Lib/Util/Input.class.php',
	    //Url地址
	    "Url" => SPAPP_PATH.'Lib/Util/Url.class.php',
		
		"Curl" => SPAPP_PATH.'Lib/Util/Curl.class.php',
	    
	    //评论处理类
	    "Comment" => APP_PATH.C("APP_GROUP_PATH")."/Comments/Util/Comment.class.php",
	
	    //分页类
	    "Page" => SPAPP_PATH.'Lib/Util/Page.class.php',
	    "phpQuery"=>SPAPP_PATH.'Lib/Extend/phpQuery/phpQuery.php',
		"ThinkOauth"=>SPAPP_PATH.'Lib/Extend/ThinkSDK/ThinkOauth.class.php',
	
		//标签库
		//"TagLibSpadmin"=>SPAPP_PATH.'Lib/Taglib/TagLibSpadmin.class.php',
		//Hook
		"Hook"=>SPAPP_PATH.'Lib/Util/Hook.class.php',
		//PHPZip
		"phpzip"=>SPAPP_PATH.'Lib/Util/phpzip.php',
		"Checkcode"=>SPAPP_PATH.'Lib/Util/Checkcode.class.php',
);
?>
