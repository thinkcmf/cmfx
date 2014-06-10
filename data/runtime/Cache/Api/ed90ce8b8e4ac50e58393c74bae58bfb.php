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
      <li class="current"><a href="<?php echo U('guestbookadmin/index');?>">所有留言</a></li>
    </ul>
  </div>
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="#">
      <div class="table_list">
	    <table width="100%">
	        <thead>
	          <tr>
	            <td width="50">ID</td>
	            <td>姓名</td>
	            <td>邮箱</td>
	            <td>留言标题</td>
	            <td>留言内容</td>
	            <td>留言时间</td>
	            <td width="120">操作</td>
	          </tr>
	        </thead>
	        <tbody>
	        	<?php if(is_array($guestmsgs)): foreach($guestmsgs as $key=>$vo): ?><tr>
		            <td><?php echo ($vo["id"]); ?></td>
		            <td><?php echo ($vo["full_name"]); ?></td>
		            <td><?php echo ($vo["email"]); ?></td>
		            <td><?php echo ($vo["title"]); ?></td>
		            <td><?php echo ($vo["msg"]); ?></td>
		            <td><?php echo ($vo["createtime"]); ?></td>
		            <td>
			            <a href="<?php echo U('guestbookadmin/delete',array('id'=>$vo['id']));?>" class="J_ajax_del" >删除</a>
			        </td>
	          	</tr><?php endforeach; endif; ?>
			</tbody>
	      </table>
	      <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
<!--     <div class="btn_wrap"> -->
<!--       <div class="btn_wrap_pd"> -->
<!--         <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">排序</button> -->
<!--       </div> -->
<!--     </div> -->
  </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js?<?php echo ($js_debug); ?>"></script>
</body>
</html>