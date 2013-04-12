<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>提示信息</title>
<style type="text/css">
*{ padding:0; margin:0; font-size:12px}
a:link,a:visited{text-decoration:none;color:#0068a6}
a:hover,a:active{color:#ff6600;text-decoration: underline}
.showMsg{border: 1px solid #1e64c8; zoom:1; width:450px; position:absolute;top:50%;left:50%;margin:-87px 0 0 -225px}
.showMsg h5{color:#444444;background-color: #e4ecf7;border-bottom:1px solid #1e64c8; padding-left:5px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
.showMsg .content{ text-align: center; line-height: 24px; padding: 20px 0}
.showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
</style>
<script type="text/javascript">
function redirect(url)
{
	location.href = url;
}
</script>
</head>
<body>
<div class="showMsg" style="text-align:center">
	<h5>提示信息</h5>
    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline; max-width:280px">
    	<?php echo $message;?>
    </div>
    <div class="bottom">
	    <?php if($url_forward=='goback' || $url_forward==''){?>
		<a href="javascript:history.back();" >[点这里返回上一页]</a>
		<?php }elseif($url_forward=="close"){?>
		<input type="button" name="close" value=" 关闭 " onClick="window.close();">
		<?php }elseif($url_forward=="blank"){?>
		<?php }elseif($url_forward){?>
		<a href="<?php echo $url_forward;?>">如果您的浏览器没有自动跳转，请点击这里</a>
		<script language="javascript">setTimeout("redirect('<?php echo $url_forward;?>');",1250);</script> 
		<?php }?>
    </div>
</div>
</body>
</html>