<?php defined('IN_Next') or exit('No permission resources.'); ?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo $title;?></title>
<link href="statics/css/reset.css" type="text/css" rel="stylesheet"/>
<link href="statics/css/user/register.css" type="text/css" rel="stylesheet"/>
<script src="statics/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="statics/js/jquery.validate.js" type="text/javascript"></script>
<script src="statics/js/form.js" type="text/javascript"></script>
</head>
<body>
	<div id="headWrap">
		<h1 id="logo">
			<a href="http://iyn.cc" title="｛X｝Management Platform"></a>
		</h1>
	</div>
	<div id="mainWrapper">
		<div class="pageTit">
			<h2>立即登录会员</h2>
			<p class="desc">发现更多精彩，享受更多便捷</p>
		</div>
		<div class="cWrap">
			<div class="method clear">
				<i class="iconMail"></i>
				<div class="inner">
					<div class="tBar">
						<h2>邮箱登录</h2>
						<p>会员登录成功后，你可以使用这个邮箱帐号享受本站的其他服务。</p>
					</div>
					<div class="form">
						<form action="" method="post" id="loginForm" class="required-validate">
							<div class="formwrap">
								<label for="email">电子邮箱:</label>
								<input type="text" name="email" id="email" class="shadowfield required email" />
							</div>
							<div class="formwrap">
								<label for="password">密　　码:</label>
								<input type="password" name="password" id="password" class="shadowfield required"  minlength="8"  maxlength="20" />
							</div>
							<div class="btn-container">
								<input class="submit" type="submit" class="blu-btn" value="登　录"/>
							</div>
							<div class="orther">
								<label for="info"><a href="index.php?m=user&c=index&a=register">立即注册会员</a>　|　<a href="#">忘记密码？</a></label>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wrapper fshadow"></div>
	<div id="Copyright">
	    <div class="wrapper">Copyright © 2012 - 2013 Ivup. All Rights Reserved</div>
	</div>
</body>
</html>