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
    <form method="post" class="J_ajaxForm" action="<?php echo U('Admin/mailer/index_post');?>">
      <div class="table_list">
	    <table width="100%">
	        <thead>
	          <tr>
	            <td colspan="2">发送邮箱SMTP配置</td>
	          </tr>
	        </thead>
	        <tbody>
	        	<tr>
	        		<td width="100">邮箱地址</td>
	        		<td>
	        			<input type="text" class="input mr5" name="address" value="<?php echo (C("SP_MAIL_ADDRESS")); ?>" />
	        			<span class="must_red">*</span>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>SMTP服务器</td>
	        		<td>
	        			<input type="text" class="input mr5" name="smtp" value="<?php echo (C("SP_MAIL_SMTP")); ?>" />
	        			<span class="must_red">*</span>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>邮箱登录帐号</td>
	        		<td>
	        			<input type="text" class="input mr5" name="loginname" value="<?php echo (C("SP_MAIL_LOGINNAME")); ?>" />
	        			<span class="must_red">*</span>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>邮箱登录密码</td>
	        		<td>
	        			<input type="password" class="input mr5" name="password" value="<?php echo (C("SP_MAIL_PASSWORD")); ?>" />
	        			<span class="must_red">*</span>
	        		</td>
	        	</tr>
			</tbody>
	    </table>
  </div>
  <div>
      <div class="btn_wrap_pd">
        <button type="submit" class="btn btn_submit mr10 J_ajax_submit_btn">确定</button>
      </div>
    </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>

</body>
</html>