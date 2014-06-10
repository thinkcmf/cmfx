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
</head>

<body>
<div class="wrap">
  <div id="home_toptip"></div>
  <h2 class="h_a">系统通知</h2>
  <div class="home_info">
    <ul  id="thinkcmf_notices">
    	<li><img src="/tpl_admin/default/res/images/loading.gif"  style="vertical-align: middle;"/><span style="display:inline-block;vertical-align: middle;">加载中...</span></li>
    </ul>
  </div>
  <h2 class="h_a">系统信息</h2>
  <div class="home_info">
    <ul>
      <?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li> <em><?php echo ($key); ?></em> <span><?php echo ($vo); ?></span> </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
  <h2 class="h_a">发起团队</h2>
  <div class="home_info" id="home_devteam">
    <ul>
      <li> <em>ThinkCMF团队</em> <span>www.thinkcmf.com</span> </li>
      <li> <em>团队成员</em> <span>Dean,Sam,Tuolaji,Codefans,Jack</span> </li>
      <li> <em>联系邮箱</em> <span>cmf@simplewind.net</span> </li>
    </ul>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script> 
<script>
//获取官方通知
$.getJSON("http://www.thinkcmf.com/service/sms_jsonp.php?callback=?",function(data){
	var tpl='<li><em class="title"></em><span class="content"></span></li>';
	var $thinkcmf_notices=$("#thinkcmf_notices");
	$thinkcmf_notices.empty();
	if(data.length>0){
		$.each(data,function(i,n){
			var $tpl=$(tpl);
			$(".title",$tpl).html(n.title);
			$(".content",$tpl).html(n.content);
			$thinkcmf_notices.append($tpl);
		});
	}else{
		$thinkcmf_notices.append("<li>^_^,没有通知~~</li>");
	}
	
});
</script>
</body>
</html>