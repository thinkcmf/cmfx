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
<div class="wrap jj">
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="#">
      <div class="table_list">
	    <table width="100%">
	        <thead>
	          <tr>
	            <td align='center'>ID</td>
	            <td>用户名</td>
	            <td>昵称</td>
	            <td>E-mail</td>
	            <td>注册时间</td>
	            <td>最后登录时间</td>
	            <td>最后登录IP</td>
	            <td align='center'>操作</td>
	          </tr>
	        </thead>
	        <tbody>
	        	<?php if(is_array($lists)): foreach($lists as $key=>$vo): ?><tr>
	            <td align='center'><?php echo ($vo["ID"]); ?></td>
	            <td><?php echo ($vo["user_login_name"]); ?></td>
	            <td><?php echo ($vo["user_nickname"]); ?></td>
	            <td><?php echo ($vo["user_email"]); ?></td>
	            <td><?php echo date('Y-m-d H:i:s', $vo['create_time']);?></td>
	            <td><?php echo date('Y-m-d H:i:s', $vo['last_login_time']);?></td>
	            <td><?php echo ($vo["last_login_ip"]); ?></td>
	            <td align='center'>
		            <a href="<?php echo U('indexadmin/delete',array('id'=>$vo['ID']));?>" class="J_ajax_del" >删除</a>
		        </td>
	          	</tr><?php endforeach; endif; ?>
			</tbody>
	      </table>
	      <div class="p10"><div class="pages"><?php echo ($page); ?></div> </div>
  </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script>
</script>
</body>
</html>