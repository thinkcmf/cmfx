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
<body>
<div class="wrap">
  <div id="error_tips">
    <h2><?php echo ($msgTitle); ?></h2>
    <div class="error_cont">
      <ul>
        <li><?php echo ($error); ?></li>
      </ul>
      <div class="error_return"><a href="<?php echo ($jumpUrl); ?>" class="btn">返回</a></div>
    </div>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script>
setTimeout(function(){
	location.href = '<?php echo ($jumpUrl); ?>';
},3000);
</script>
</body>
</html>