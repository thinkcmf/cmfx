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
      <li class="current"><a href="<?php echo U('term/index');?>">分类管理 </a></li>
      <li><a href="<?php echo U('term/add');?>">添加分类 </a></li>
    </ul>
  </div>
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="<?php echo U('term/listorders');?>">
      <div class="table_list">
	    <table width="100%">
	        <thead>
	          <tr>
	            <td width="80">排序</td>
	            <td width="100">ID</td>
	            <td>分类名称</td>
	            <td>分类类型</td>
	            <td align='center'>访问</td>
	            <td>操作</td>
	          </tr>
	        </thead>
	        <tbody>
	        	<?php echo ($taxonomys); ?>
			</tbody>
	      </table>
    <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button>
      </div>
    </div>
  </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js?<?php echo ($js_debug); ?>"></script>
</body>
</html>