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
      <li class="current"><a href="<?php echo U('user/index');?>">管理员</a></li>
      <li><a href="<?php echo U('user/add');?>">添加管理员</a></li>
    </ul>
   </div>
   <div class="table_list">
   <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td width="50">ID</td>
            <td>用户名</td>
            <td>所属角色</td>
            <td>最后登录IP</td>
            <td>最后登录时间</td>
            <td>E-mail</td>
            <td width="120">管理操作</td>
          </tr>
        </thead>
        <tbody>
        <?php if(is_array($users)): foreach($users as $key=>$vo): ?><tr>
            <td><?php echo ($vo["ID"]); ?></td>
            <td><?php echo ($vo["user_login"]); ?></td>
            <td><?php echo ($roles[$vo['role_id']]['name']); ?></td>
            <td><?php echo ($vo["last_login_ip"]); ?></td>
            <td>
	            <?php if($vo['last_login_time'] == 0): ?>该用户还没登陆过
	            <?php else: ?>
	            <?php echo ($vo["last_login_time"]); endif; ?>
            </td>
            <td><?php echo ($vo["user_email"]); ?></td>
            <td>
	            <?php if($vo['ID'] == 1): ?><font color="#cccccc">修改</font> | 
	            <font color="#cccccc">删除</font>
	            <?php else: ?>
	            <a href='<?php echo U("user/edit",array("id"=>$vo["ID"]));?>'>修改</a> | 
	            <a class="J_ajax_del" href="<?php echo U('user/delete',array('id'=>$vo['ID']));?>">删除</a><?php endif; ?>
            </td>
          </tr><?php endforeach; endif; ?>
        </tbody>
      </table>
   </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
</body>
</html>