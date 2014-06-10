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
      <li class="current"><a href="<?php echo U('nav/index');?>">前台菜单</a></li>
      <li><a href="<?php echo U('nav/add');?>">添加菜单</a></li>
    </ul>
  </div>
  <div class="h_a">搜索</div>
  <form id="mainform" action="<?php echo U('nav/index');?>" method="post" >
	  <div class="search_type cc mb10">
	      <div class="mb10">
		  	<select id="navcid_select" name="cid" class="normal_select">
		  		<?php if(is_array($navcats)): foreach($navcats as $key=>$vo): $navcid_selected=$navcid==$vo['navcid']?"selected":""; ?>
		             <option value="<?php echo ($vo["navcid"]); ?>" <?php echo ($navcid_selected); ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
		  	</select>
	  	</div>
	  </div>
	</form>
  <form class="J_ajaxForm" action="<?php echo U('nav/listorders');?>" method="post" >
  	
    <div class="table_list">
      <table width="100%">
        <colgroup>
        <col width="80">
        <col width="100">
        <col>
        <col width="80">
        <col width="200">
        </colgroup>
        <thead>
          <tr>
            <td>排序</td>
            <td>ID</td>
            <td>菜单英文名称</td>
            <td>状态</td>
            <td>管理操作</td>
          </tr>
        </thead>
        <?php echo ($categorys); ?>
      </table>
    </div>
    <div class="btn_wrap">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button>
      </div>
    </div>
  </form>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script>
$(function(){
	
	$("#navcid_select").change(function(){
		$("#mainform").submit();
	});
	
	
});


</script>
</body>
</html>