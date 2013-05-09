<?php defined('IN_Next') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>｛X｝Management Platform</title>
<link href="statics/css/admin/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/css/admin/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/css/admin/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="statics/js/admin/uploadify/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="statics/css/admin/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->
<!--[if lte IE 9]>
<script src="statics/js/admin/speedup.js" type="text/javascript"></script>
<![endif]-->
<script src="statics/js/admin/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.cookie.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.validate.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.bgiframe.js" type="text/javascript"></script>
<script src="statics/js/admin/xheditor/xheditor-1.2.1.min.js" type="text/javascript"></script>
<script src="statics/js/admin/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
<script src="statics/js/admin/xheditor/xheditor_plugins/ubb.js" type="text/javascript"></script>
<script src="statics/js/admin/uploadify/jquery.uploadify-3.1.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.treetable.js"></script>
<script src="statics/js/admin/dwz.core.js" type="text/javascript"></script>
<script src="statics/js/admin/dwz.regional.zh.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	DWZ.init("statics/xml/js.xml", {
		loginUrl:"<?php echo $this->_context->url('index::logout@admin'); ?>",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:true,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
		}
	});
});
</script>
</head>
<body scroll="no">
	<div id="layout">
		<div id="header">
			<ul class="nav">
				<li id="switchEnvBox"><a href="javascript:">（<span><?php echo $currentsite['name'];?></span>）切换站点</a>
					<ul>
						<?php if(is_array($sitelist)) foreach($sitelist AS $key => $v) { ?>
						<li><a href="<?php echo $this->_context->url('index::setSiteid@admin','siteid/'.$v['siteid']); ?>" icon=""><?php echo $v['name'];?></a></li>
						<?php } ?>
					</ul>
				</li>
				<li>您好！<?php echo $email;?>  [<?php echo $rolename;?>]</li>
				<li><a href="<?php echo $this->_context->url('index::logout@admin'); ?>">退出</a></li>
			</ul>
			<div id="navMenu">
				<a id="logo" class="logo" href="<?php echo $currentsite['domain'];?>" style="background-image: url(<?php echo $currentsite['logo'];?>) no-repeat;"><?php echo $currentsite['name'];?></a>
				<ul>
					<?php if(is_array($menu_arr)) foreach($menu_arr AS $_value) { ?>
					<li<?php if($_value['id']==4) { ?> class="selected"<?php } ?>><a href="<?php echo $this->_context->url('index::menuLeft@admin','menuid/'.$_value['id']); ?>"><span><?php echo $_value['name'];?></span></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<div id="leftside">
			<div id="sidebar">
				<div class="treeMenu" fillSpace="sidebar">
					<ul class="tree treeFolder expand">
						<?php
						foreach($init_left_menu as $_left_menu) {
							echo '<li><a>'.$_left_menu['name'].'</a><ul>';
							$sub_array = $this->admin_menu($_left_menu['id']);
							foreach($sub_array as $_key=>$_m) {
								//附加参数
								$data = $_m['data'] ? '&'.$_m['data'] : '';
								echo '<li><a href="'.$this->_context->url($_m['c'].'::'.$_m['a'].'@'.$_m['m']).$data.'" target="navTab" rel="'.$_m['m'].'_'.$_m['c'].'_'.$_m['a'].'">'.$_m['name'].'</a></li>';
							}
							echo '</ul></li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="accountInfo">
							<p><span>NextPHP</span></p>
							<p>NextPHP GitHub 主页：<a href="http://ivupcn.github.io/NextPHPFramework/" target="_blank">http://ivupcn.github.io/NextPHPFramework/</a></p>
						</div>
						<div class="pageFormContent" layoutH="80">
							<h2></h2>
							<div class="divider"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>