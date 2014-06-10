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
      <li class="current"><a href="<?php echo U('Page/index');?>">所有页面</a></li>
      <li ><a href="<?php echo U('Page/add');?>" >添加页面</a></li>
    </ul>
  </div>
  <!--
  <div class="mb10">
		<a href="<?php echo U('page/add');?>" target="_blank" class="btn" title="添加页面"><span class="add"></span>添加页面</a>
  </div>
  -->
  <div class="h_a">搜索</div>
  <form method="post" action="<?php echo u('page/index');?>">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">时间：
        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ($formget["start_time"]); ?>" style="width:80px;" autocomplete="off">-<input autocomplete="off" type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width:80px;">
        <!-- 
        <select class="select_2" name="posids"style="width:70px;">
          <option value='' selected>全部</option>
        </select>
        <select class="select_2" name="searchtype" style="width:70px;">
          <option value='0' >标题</option>
        </select>
         -->
                           关键字：
        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="请输入关键字...">
        <button class="btn">搜索</button>
        </span>
      </div>
    </div>
  </form>
  <form class="J_ajaxForm" action="" method="post">
    <div class="table_list">
      <table width="100%">
        <thead>
	          <tr>
	            <td width="16"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
	            <td width="100">ID</td>
	            <td>标题</td>
	            <!-- <td>点击量</td> -->
	            <td width="80">发布人</td>
	            <td width="120"><span>发布时间</span></td>
	            <td width="120">操作</td>
	          </tr>
	    </thead>
        	<?php if(is_array($posts)): foreach($posts as $key=>$vo): ?><tr>
		            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["ID"]); ?>"></td>
		            <td><a><?php echo ($vo["ID"]); ?></a></td>
		            <td>
		            	<a href="<?php echo U('portal/page/index',array('id'=>$vo['ID']));?>" target="_blank"><span><?php echo ($vo["post_title"]); ?></span></a>
		            </td>
		           <!--  <td>0</td> -->
		            <td><?php echo ($users[$vo['post_author']]['user_login']); ?></td>
		            <td><?php echo ($vo["post_date"]); ?></td>
		            <td>
		            	<a href="<?php echo U('page/edit',array('id'=>$vo['ID']));?>" target="_blank" >修改</a>|
		            	<a href="<?php echo U('page/delete',array('id'=>$vo['ID']));?>" class="J_ajax_del" >删除</a>
					</td>
	          	</tr><?php endforeach; endif; ?>
          </table>
      <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
     
    </div>
    <div>
      <div class="btn_wrap_pd">
        <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>                
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo U('page/delete');?>" data-subcheck="true">删除</button>
      </div>
    </div>
  </form>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script>
setCookie('refersh_time', 0);
function refersh_window() {
    var refersh_time = getCookie('refersh_time');
    if (refersh_time == 1) {
        window.location.reload();
    }
}
setInterval(function(){
	refersh_window()
}, 2000);

</script>
</body>
</html>