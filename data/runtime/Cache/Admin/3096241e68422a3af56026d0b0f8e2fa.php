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
  <div class="common-form">
    <form method="post" class="J_ajaxForm" action="<?php echo U('Admin/mailer/active_post');?>">
      <div class="table_list">
	    <table width="100%">
	        <thead>
	          <tr>
	            <td colspan="2">用户邮箱激活设置</td>
	          </tr>
	        </thead>
	        <tbody>
	        	<tr>
	        		<td width="100">邮箱激活</td>
	        		<td>
	        			<?php $radio1=''; $radio2=' checked'; if(C('SP_MEMBER_EMAIL_ACTIVE')=='true'){ $radio1=' checked'; $radio2=''; } ?>
	        			<label for="lightup_true">开启</label><input type="radio" <?php echo ($radio1); ?> id="lightup_true" class="radio" name="lightup" value="true" />
	        			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        			<label for="lightup_false">关闭</label><input type="radio" <?php echo ($radio2); ?> id="lightup_false" class="radio" name="lightup" value="false" />
	        			<input type="hidden" name="option_id" value="" />
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>邮件标题</td>
	        		<td><input type="text" class="input mr5" name="options[title]" value="<?php echo ($options["title"]); ?>" /></td>
	        	</tr>
	        	<tr>
	        		<td>邮件模版</td>
	        		<td>
	        		<script type="text/javascript">
	                //编辑器路径定义
	                var editorURL = GV.DIMAUB;
	                </script>
	                <script type="text/javascript"  src="/statics/js/ueditor/ueditor.config.js<?php echo ($js_debug); ?>"></script>
	                <script type="text/javascript"  src="/statics/js/ueditor/ueditor.all.min.js<?php echo ($js_debug); ?>"></script>
					<script type="text/javascript">
					    var editorcontent = new baidu.editor.ui.Editor();
					    editorcontent.render('content');
					</script>
	        		<script type="text/plain" id="content" name="options[template]"><?php echo ($options["template"]); ?></script>
					<style type="text/css">
					.content_attr {
						border: 1px solid #CCC;
						padding: 5px 8px;
						background: #FFC;
						margin-top: 6px
					}
					</style>
	        		<span style="color:#ffb752;">请用http://#link#代替激活链接，#username#代替用户名</span>
					</td>
	        	</tr>
			</tbody>
	    </table>
  </div>
  <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <button type="submit" class="btn btn_submit mr10 J_ajax_submit_btn">确定</button>
      </div>
    </div>
    </form>
  </div>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
</body>
</html>