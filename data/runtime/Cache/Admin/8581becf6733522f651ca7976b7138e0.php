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
	        <li class="current"><a href="<?php echo U('User/userinfo');?>">修改信息</a></li>
	      </ul>
  </div>
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="<?php echo U('User/userinfo_post');?>">
      <div class="h_a">个人信息</div>
      <div class="table_list">
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
          <tbody>
            <tr>
              <td>昵称:</td>
              <td>
               <input type="hidden" class="input" name="ID" value="<?php echo ($ID); ?>">
              <input type="text" class="input" name="user_nicename" value="<?php echo ($user_nicename); ?>">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="">
        <div class="btn_wrap_pd">
          <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">更新</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>

</body>
</html>