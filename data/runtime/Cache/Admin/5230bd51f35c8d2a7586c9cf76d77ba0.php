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
<head/>
<body>
<div class="wrap J_check_wrap">
  <div class="nav">
    <ul class="cc">
      <li class="current"><a href="<?php echo U('rbac/index');?>">角色管理</a></li>
      <li ><a href="<?php echo U('rbac/roleadd');?>" >添加角色</a></li>
    </ul>
  </div>
  <div class="table_list">
  <form name="myform" action="<?php echo U('Rbac/listorders');?>" method="post">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <td width="30">ID</td>
          <td align="left" >角色名称</td>
          <td align="left" >角色描述</td>
          <td width="40" align="left" >状态</td>
          <td width="200">管理操作</td>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><tr>
          <td><?php echo ($vo["id"]); ?></td>
          <td><?php echo ($vo["name"]); ?></td>
          <td><?php echo ($vo["remark"]); ?></td>
          <td>
          <?php if($vo['status'] == 1): ?><font color="red">√</font>
          <?php else: ?>
          <font color="red">╳</font><?php endif; ?>
          </td>
          <td  class="text-c">
          <?php if($vo['id'] == 1): ?><font color="#cccccc">权限设置</font> | <a href="javascript:open_iframe_dialog('<?php echo U('rbac/member',array('id'=>$vo['id']));?>','成员管理');">成员管理</a> | <font color="#cccccc">修改</font> | <font color="#cccccc">删除</font>
          <?php else: ?>
          <a href="<?php echo U('Rbac/authorize',array('id'=>$vo['id']));?>">权限设置</a>  |<a href="javascript:open_iframe_dialog('<?php echo U('rbac/member',array('id'=>$vo['id']));?>','成员管理');">成员管理</a>| <a href="<?php echo U('Rbac/roleedit',array('id'=>$vo['id']));?>">修改</a> | <a class="J_ajax_del" href="<?php echo U('Rbac/roledelete',array('id'=>$vo['id']));?>">删除</a><?php endif; ?>
          </td>
        </tr><?php endforeach; endif; ?>
      </tbody>
    </table>
  </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
</body>
</html>