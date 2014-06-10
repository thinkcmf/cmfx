<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta charset="utf-8">
<title>ThinkCMF</title>

<meta name="description" content="This is page-header (.page-header &gt; h1)">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="/tpl_admin/default/res/reset.css?<?php echo ($js_debug); ?>" rel="stylesheet">
<link href="/tpl_admin/default/res/bootstrap.min.css?<?php echo ($js_debug); ?>" rel="stylesheet">
<!-- <link href="/tpl_admin/default/res/bootstrap-responsive.min.css?<?php echo ($js_debug); ?>" rel="stylesheet"> -->
<link rel="stylesheet" href="/tpl_admin/default/res/css/font-awesome.min.css?<?php echo ($js_debug); ?>">
<!--[if IE ]>
		  <link rel="stylesheet" href="/tpl_admin/default/res/css/font-awesome-ie7.min.css?<?php echo ($js_debug); ?>" />
<![endif]-->

<link rel="stylesheet" href="/tpl_admin/default/res/prettify.css?<?php echo ($js_debug); ?>">
<link rel="stylesheet" href="/tpl_admin/default/res/css">
<link rel="stylesheet" href="/tpl_admin/default/res/ace.min.css?<?php echo ($js_debug); ?>">


<!-- <link rel="stylesheet" href="/tpl_admin/default/res/ace-responsive.min.css?<?php echo ($js_debug); ?>"> -->
<link rel="stylesheet" href="/tpl_admin/default/res/ace-skins.min.css?<?php echo ($js_debug); ?>">

<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/tpl_admin/default/res/ace-ie.min.css?<?php echo ($js_debug); ?>" />
		<![endif]-->
<link rel="stylesheet" href="/tpl_admin/default/res/style_new.css?<?php echo ($js_debug); ?>">
<link rel="stylesheet" href="/tpl_admin/default/res/theme_blue.css?<?php echo ($js_debug); ?>">
<link rel="stylesheet" href="/tpl_admin/default/res/hack.css?<?php echo ($js_debug); ?>">
<script>
//全局变量
var GV = {
	HOST:"<?php echo ($_SERVER['HTTP_HOST']); ?>",
    DIMAUB: "/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<?php $submenus=(array)json_decode($SUBMENU_CONFIG); ?>

<?php function getsubmenu($submenus){ ?>
<?php foreach($submenus as $menu){ ?>
					<li>
						<?php if(empty($menu->items)){ ?>
							<a href="javascript:openapp('<?php echo ($menu->url); ?>','<?php echo ($menu->id); ?>','<?php echo ($menu->name); ?>');">
								<i class="fa fa-<?php echo ((isset($menu->icon) && ($menu->icon !== ""))?($menu->icon):'desktop'); ?>"></i>
								<span class="menu-text"><?php echo ($menu->name); ?></span>
							</a>
						<?php }else{ ?>
							<a href="#" class="dropdown-toggle">
								<i class="fa fa-<?php echo ((isset($menu->icon) && ($menu->icon !== ""))?($menu->icon):'desktop'); ?>"></i>
								<span class="menu-text"><?php echo ($menu->name); ?></span>
								<b class="arrow fa fa-angle-down"></b>
							</a>
							<ul  class="submenu">
									<?php getsubmenu1((array)$menu->items) ?>
							</ul>	
						<?php } ?>
						
					</li>
					
				<?php } ?>
<?php } ?>

<?php function getsubmenu1($submenus){ ?>
<?php foreach($submenus as $menu){ ?>
					<li>
						<?php if(empty($menu->items)){ ?>
							<a href="javascript:openapp('<?php echo ($menu->url); ?>','<?php echo ($menu->id); ?>','<?php echo ($menu->name); ?>');">
								<i class="fa fa-angle-double-right"></i>
								<span class="menu-text"><?php echo ($menu->name); ?></span>
							</a>
						<?php }else{ ?>
							<a href="#" class="dropdown-toggle">
								<i class="fa fa-angle-double-right"></i>
								<span class="menu-text"><?php echo ($menu->name); ?></span>
								<b class="arrow fa fa-angle-down"></b>
							</a>
							<ul  class="submenu">
									<?php getsubmenu2((array)$menu->items) ?>
							</ul>	
						<?php } ?>
						
					</li>
					
				<?php } ?>
<?php } ?>

<?php function getsubmenu2($submenus){ ?>
<?php foreach($submenus as $menu){ ?>
					<li>
						<a href="javascript:openapp('<?php echo ($menu->url); ?>','<?php echo ($menu->id); ?>','<?php echo ($menu->name); ?>');">
								<i class="fa fa-leaf"></i>
								<span class="menu-text"><?php echo ($menu->name); ?></span>
							</a>
					</li>
					
				<?php } ?>
<?php } ?>


<?php if(APP_DEBUG): ?><style>
#think_page_trace_open{left: 0 !important;
right: initial !important;}			
</style><?php endif; ?>

</head>

<body style="min-width:900px;" screen_capture_injected="true">
	<div id="loading"><i class="loadingicon"></i><span>正在加载...</span></div>
	<div id="right_tools_wrapper">
		<!--<span id="right_tools_clearcache" title="清除缓存" onclick="javascript:openapp('<?php echo u('admin/setting/clearcache');?>','right_tool_clearcache','清除缓存');"><i class="fa fa-trash-o right_tool_icon"></i></span>
		--><span id="refresh_wrapper" title="刷新当前页" ><i class="fa fa-refresh right_tool_icon"></i></span>
	</div>
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a href="/index.php?g=admin&m=index&a=index" class="brand"> <small> 
				<img src="/statics/images/icon/logo-18.png">
						ThinkCMF 后台
				</small>
				</a>
				<ul class="nav ace-nav pull-right">
					<li class="light-blue">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<img class="nav-user-photo" src="/tpl_admin/default/res/user.jpg" alt="<?php echo ($admin["user_login"]); ?>">
							<span class="user-info">
								<small>欢迎,</small><?php echo ((isset($admin["user_nicename"]) && ($admin["user_nicename"] !== ""))?($admin["user_nicename"]):$admin[user_login]); ?>
							</span>
							<i class="fa fa-caret-down"></i>
						</a>
						<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
							<li><a href="javascript:openapp('<?php echo u('setting/site');?>','index_site','站点管理');"><i class="fa fa-cog"></i>站点管理</a></li>
							<li><a href="javascript:openapp('<?php echo u('user/userinfo');?>','index_userinfo','个人资料');"><i class="fa fa-user"></i>个人资料</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo U('Public/logout');?>"><i class="fa fa-off"></i>退出</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="main-container container-fluid">
		<a class="menu-toggler" id="menu-toggler"
			href="#">
			<span class="menu-text"></span>
		</a>

		<div class="sidebar" id="sidebar">
			<div class="sidebar-shortcuts" id="sidebar-shortcuts">
				<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
					<a class="btn btn-small btn-success" href="javascript:openapp('<?php echo u('term/index');?>','index_termlist','分类管理');" title="分类管理">
						<i class="fa fa-th"></i>
					</a>

					
					<a class="btn btn-small btn-info" href="javascript:openapp('<?php echo u('post/index');?>','index_postlist','文章管理');" title="文章管理">
						<i class="fa fa-pencil"></i>
					</a>

					<a class="btn btn-small btn-warning" href="/" title="前台首页" target="_blank">
						<i class="fa fa-home"></i>
					</a>

					<a class="btn btn-small btn-danger" href="javascript:openapp('<?php echo u('admin/setting/clearcache');?>','index_clearcache','清除缓存');" title="清除缓存">
						<i class="fa fa-trash-o"></i>
					</a>
				</div>

				<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
					<span class="btn btn-success"></span> <span class="btn btn-info"></span>

					<span class="btn btn-warning"></span> <span class="btn btn-danger"></span>
				</div>
			</div>
			<div class="sidebar-collapse" id="sidebar-collapse">
				<i class="fa fa-angle-double-left"></i>
			</div>
			<div id="nav_wraper">
			<ul class="nav nav-list">
				<?php echo getsubmenu($submenus);?>
			</ul>
			</div>
			
		</div>

		<div class="main-content">
			<div class="breadcrumbs" id="breadcrumbs">
				<!-- <ul class="breadcrumb">
					<li><i class="fa fa-home home-icon"></i> <a
						href="#">首页</a>

						<span class="divider"> <i
							class="fa fa-angle-right arrow-icon"></i>
					</span></li>
					<li class="active">Typography</li>
				</ul> -->
				<a id="task-pre" class="task-changebt">←</a>
				<div id="task-content">
				<ul class="macro-component-tab" id="task-content-inner">
					<li class="macro-component-tabitem noclose" app-id="0" app-url="<?php echo u('main/index');?>" app-name="首页">
						<span class="macro-tabs-item-text">首页</span>
					</li>
					
					
				</ul>
				<div style="clear:both;"></div>
				</div>
				<a id="task-next" class="task-changebt">→</a>

			</div>

			<div class="page-content" id="content">

				<iframe src="<?php echo U('Main/index');?>" style="width:100%;height: 100%;" frameborder="0" id="appiframe-0" class="appiframe"></iframe>
			
			
			</div>

			
		</div>
	</div>
	
			<script src="/tpl_admin/default/res/jquery-1.7.2.min.js?<?php echo ($js_debug); ?>"></script>
	
			<script type="text/javascript">
				if("ontouchend" in document) document.write("<script src='/tpl_admin/default/res/jquery.mobile.custom.min.js'>"+"<"+"/script>");
			</script>

			<script src="/tpl_admin/default/res/bootstrap.min.js?<?php echo ($js_debug); ?>"></script>

			<script src="/tpl_admin/default/res/prettify.js?<?php echo ($js_debug); ?>"></script>

			<script src="/tpl_admin/default/res/ace-elements.min.js?<?php echo ($js_debug); ?>"></script>
			<script src="/tpl_admin/default/res/ace.min.js?<?php echo ($js_debug); ?>"></script>
			<script src="/tpl_admin/default/res/jquery.nicescroll.js?<?php echo ($js_debug); ?>"></script>
			<script src="/tpl_admin/default/res/script.js?<?php echo ($js_debug); ?>"></script>
			<script src="/tpl_admin/default/res/index.js?<?php echo ($js_debug); ?>"></script>



	<script type="text/javascript">
		$(function() {

			window.prettyPrint && prettyPrint();
			$('#id-check-horizontal')
					.removeAttr('checked')
					.on(
							'click',
							function() {
								$('#dt-list-1')
										.toggleClass('dl-horizontal')
										.prev()
										.html(
												this.checked ? '&lt;dl class="dl-horizontal"&gt;'
														: '&lt;dl&gt;');
							});

		})
	</script>


</body>
</html>