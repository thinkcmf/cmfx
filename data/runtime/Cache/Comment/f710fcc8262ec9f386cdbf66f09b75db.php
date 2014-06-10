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
<body class="J_scroll_fixed" style="min-width:800px;">
<div class="wrap J_check_wrap">
  <form class="J_ajaxForm" action="" method="post">
    <div class="table_list">
      <table width="100%">
        <thead>
	          <tr>
	            <td width="16"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
	            <td width="50">ID</td>
	            <td>用户名/姓名</td>
	            <td>邮箱</td>
	            <td width="140">内容</td>
	            <td width="140"><span>评论时间</span></td>
	            <td width="50"><span>状态</span></td>
	            <td width="120">操作</td>
	          </tr>
        </thead>
        	<?php $status=array("1"=>"已审核","0"=>"未审核"); ?>
        	<?php if(is_array($comments)): foreach($comments as $key=>$vo): ?><tr>
		            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["id"]); ?>" ></td>
		            <td><?php echo ($vo["id"]); ?></td>
		            <td><?php echo ($vo["full_name"]); ?></td>
		            <td><?php echo ($vo["email"]); ?></td>
		            <td><?php echo ($vo["content"]); ?></td>
		            <td><?php echo ($vo["createtime"]); ?></td>
		            <td><?php echo ($status[$vo['status']]); ?></td>
		            <td>
		            	<a href="<?php echo U('Commentadmin/delete',array('id'=>$vo['id']));?>" class="J_ajax_del" >删除</a>
					</td>
	          	</tr><?php endforeach; endif; ?>
          </table>
      <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
     
    </div>
    <div>
      <div class="btn_wrap_pd">
        <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>                
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('Commentadmin/check',array('check'=>1));?>" data-subcheck="true">审核</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('Commentadmin/check',array('uncheck'=>1));?>" data-subcheck="true">取消审核</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('Commentadmin/delete');?>" data-subcheck="true" data-msg="你确定删除吗？">删除</button>
      </div>
    </div>
  </form>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
</body>
</html>