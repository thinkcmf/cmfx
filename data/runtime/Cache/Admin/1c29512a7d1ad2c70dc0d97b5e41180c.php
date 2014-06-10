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
  <div class="nav">
  	<ul class="cc">
        <li class="current"><a href="<?php echo U('setting/password');?>">修改密码</a></li>
    </ul>
  </div>
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="<?php echo U('setting/password_post');?>">
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
          <tbody>
            <tr>
              <td width="100">原始密码:</td>
              <td><input type="password" class="input" name="old_password" id="old_password" value="" style="width:250px;"><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>新密码:</td>
              <td><input type="password" class="input" name="password" id="password" value="" style="width:250px;"><span class="must_red">*</span></td>
            </tr>
            <tr>
              <td>重复新密码:</td>
              <td><input type="password" class="input" name="repassword" id="repassword" value="" style="width:250px;"><span class="must_red">*</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="btn_wrap">
        <div class="btn_wrap_pd">
          	<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">更新</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script type="text/javascript" src="/statics/js/content_addtop.js<?php echo ($js_debug); ?>"></script>
<script>

</script>
</body>
</html>