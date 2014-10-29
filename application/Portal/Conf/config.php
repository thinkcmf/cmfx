<?php

$runtime_home_config= array();
$configs= array(
		
		'TMPL_EXCEPTION_FILE'   => SPSTATIC.'exception.html',// 异常页面的模板文件
		'TMPL_TEMPLATE_SUFFIX'  => '.html',     // 默认模板文件后缀
 		'TMPL_FILE_DEPR'        =>  '/', //模板文件MODULE_NAME与ACTION_NAME之间的分割符
 		
);

return  array_merge($configs,$runtime_home_config);
