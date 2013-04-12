<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>｛X｝Management Platform</title>
<link href="statics/css/admin/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/css/admin/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/css/admin/print.css" rel="stylesheet" type="text/css" media="print"/>
<!--[if IE]>
<link href="statics/css/admin/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->
<link type="text/css" href="statics/css/admin/login.css" rel="stylesheet" media="screen" />
<script src="statics/js/admin/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.cookie.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.validate.js" type="text/javascript"></script>
<script src="statics/js/admin/jquery.bgiframe.js" type="text/javascript"></script>
<script src="statics/js/admin/dwz.core.js" type="text/javascript"></script>
<script src="statics/js/admin/dwz.regional.zh.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	DWZ.init("statics/xml/js.xml", {
		loginUrl:"{url 'index::logout@admin'}",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
		}
	});
});
function loginAjaxDone(json)
{
    DWZ.ajaxDone(json);
    if (json.statusCode == DWZ.statusCode.ok){
    	window.location.href=json.forwardUrl;
    }
}
</script>
</head>
<body onload="javascript:document.myform.email.focus();">
	<div id="container_box">
		<h1>｛X｝Management Platform</h1>
		<div id="box">
			<form action="{url 'index::login@admin'}" method="post" name="myform" onsubmit="return validateCallback(this,loginAjaxDone);">
				<div class="main">
					<button type="submit" class="login" />登录</button>
					<label>EMail：</label>
					<input name="email" type="text" value="" />
					<label>密码：</label>
					<input name="password" type="password" value="" />
					<label>验证码：</label>
					<input name="code" type="text" class="code" onfocus="document.getElementById('yzm').style.display='block'" />
					<div id="yzm" class="yzm">
						<?php echo form::checkcode('code_img')?>
						<br />
						<a href="javascript:document.getElementById('code_img').src='api.php?m=admin&a=checkcode&time='+Math.random();void(0);">单击更换验证码</a>
					</div>

				</div>
			</form>
		</div>
	</div>
</body>
</html>
