<?php defined('IN_Next') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php $siteinfo = $this->get_siteinfo();?>
<title><?php if(isset($siteinfo['name'])) { ?><?php echo $siteinfo['name'];?><?php } ?></title>
<link href="statics/css/reset.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/user/header.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/<?php echo ROUTE_M;?>/main.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/user/footer.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/jquery.ui.theme.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/jquery.ui.datepicker.css" type="text/css" rel="stylesheet"/>
<script src="statics/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="statics/js/jquery.validate.js" type="text/javascript"></script>
<script src="statics/js/jquery.ui.core.js" type="text/javascript"></script>
<script src="statics/js/jquery.ui.widget.js" type="text/javascript"></script>
<script src="statics/js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="statics/js/form.js" type="text/javascript"></script>
</head>
<body>
	<div id="headWrap">
		<div class="bar">
			<div class="quicklinks">
				<ul class="leftInside">
					<?php if(isset($siteinfo['name'])) { ?>
					<li class="menupop switchEnvBox">
						<a href="" class="ab-item logo"><span class="icon"></span></a>
					</li>
					<li class="menupop switchEnvBox">
						<a href="" class="ab-item"><?php echo $siteinfo['name'];?></a>
					</li>
					<?php } ?>
				</ul>
				<ul class="rightInside">
					<?php if($_SESSION['userid']) { ?>
					<li class="menupop switchEnvBox">
						<a href="" class="ab-item"><?php echo $_SESSION['email'];?></a>
					</li>
					<li class="menupop switchEnvBox">
						<a href="<?php echo $this->_context->url('index::logout@user');?>" class="ab-item">退出</a>
					</li>
					<?php } else { ?>
					<li class="menupop switchEnvBox">
						<a href="<?php echo $this->_context->url('index::login@user');?>" class="ab-item">登录</a>
					</li>
					<li class="menupop switchEnvBox">
						<a href="<?php echo $this->_context->url('index::register@user');?>" class="ab-item">注册</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div id="wrapper">
		<div id="profile_cover">
			<div class="cover_pic" style="background-image:url(http://iyn.cc/images/public/034.jpg)">
				<div class="cover_enter">
					<a href="" width="605" height="400" class="enter" title="封面设置"></a>
				</div>
			</div>
		</div>
		<div class="profile_info">
			<div class="profile_header">
				<div class="profile_top">
					<div class="pf_info clearfix">
						<div class="pf_info_left">
							<div class="pl_profile_myInfo">
								<div class="pf_name clearfix">
									<span class="name">
										<?php $realname = $_SESSION['realname']?>
										<?php $email = $_SESSION['email']?>
										<?php if(isset($realname)) { ?>
										<?php echo $realname;?>
										<?php } else { ?>
										<?php echo $email;?>
										<?php } ?>
									</span>
								</div>
								<div class="pf_intro">在“孤独”的哲学里我不能免俗，需要活在众多看客之中！</div>
							</div>
						</div>
						<div class="pf_info_right">
							
						</div>
					</div>
					<div class="pf_head">
						<div class="pl_profile_photo">
							<div class="pf_head_pic">
								<img alt="" src="http://iyn.cc/images/public/nono.png">
							</div>
						</div>
					</div>
				</div>
				<div class="post_area">
					
				</div>
			</div>
			<div class="profile_nav">
				<div class="profile_tabbig">
					<ul class="pftb_ul">
						<?php $menu_arr = $this->user_menu(2);?>
						<?php if(is_array($menu_arr)) foreach($menu_arr AS $_value) { ?>
						<li class="pftb_itm">
							<a href="<?php echo $this->_context->url($_value['c'].'::'.$_value['a'].'@'.$_value['m'],'menuid/'.$_value['id']);?>" class="pftb_lk<?php if(ROUTE_M == $_value['m']) { ?> current<?php } ?>"><span class="icobg"><em class="ico_pm ico_pmhome"></em></span><?php echo $_value['name'];?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>