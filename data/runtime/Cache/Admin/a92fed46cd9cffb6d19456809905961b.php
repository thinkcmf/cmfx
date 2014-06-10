<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
<title>系统后台</title>
<link href="/statics/css/admin_style.css<?php echo ($js_debug); ?>" rel="stylesheet" />
<link href="/statics/js/artDialog/skins/default.css<?php echo ($js_debug); ?>" rel="stylesheet" />
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<script src="/statics/js/wind.js<?php echo ($js_debug); ?>"></script>
<script src="/statics/js/jquery.js<?php echo ($js_debug); ?>"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <div class="nav">
    <ul class="cc">
      <li class="current"><a href="<?php echo U('navcat/index');?>">菜单分类</a></li>
      <li><a href="<?php echo U('navcat/add');?>">添加分类</a></li>
    </ul>
  </div>
  <form class="J_ajaxForm" action="" method="post">
    <div class="table_list">
      <table width="100%">
        <thead>
	          <tr>
	            <td width="100">ID</td>
	            <td>名称</td>
	            <td>描述</td>
	            <td width="120">主菜单</td>
	            <td width="120">操作</td>
	          </tr>
        </thead>
        	<?php if(is_array($navcats)): foreach($navcats as $key=>$vo): ?><tr>
		            <td><?php echo ($vo["navcid"]); ?></td>
		            <td><?php echo ($vo["name"]); ?></td>
	            	<td><?php echo ($vo["remark"]); ?></td>
	            	<td>
					<?php $mainmenu=$vo['active']?"是":"否"; ?>
					<?php echo ($mainmenu); ?>
					</td>
		            <td>
		            	<a href="<?php echo U('navcat/edit',array('id'=>$vo['navcid']));?>">修改</a>|
		            	<a href="<?php echo U('navcat/delete',array('id'=>$vo['navcid']));?>" class="J_ajax_del" >删除</a>
					</td>
	          	</tr><?php endforeach; endif; ?>
          </table>
      <div class="p10"><div class="pages">  </div> </div>
     
    </div>
  	</form>
</div>
<script src="/statics/js/common.js?<?php echo ($js_debug); ?>"></script>
</body>
</html>