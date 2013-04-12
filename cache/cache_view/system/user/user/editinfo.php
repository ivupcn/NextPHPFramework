<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">

<div class="pad_10">
	<h2 class="contentTitle">修改个人信息</h2>
<form action="?m=user&c=user&a=editInfo" method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
	<div class="pageFormContent" layoutH="95">

<input type="hidden" name="info[userid]" value="<?php echo $userid?>">
<input type="hidden" name="info[email]" value="<?php echo $email?>">
<table width="100%" class="table_form contentWrap">
<tr>
<td width="100">Email：</td> 
<td><?php echo $email?></td>
</tr>

<tr>
<td width="100">最后登录时间：</td> 
<td><?php echo $lastlogintime ? date('Y-m-d H:i:s',$lastlogintime) : ''?></td>
</tr>

<tr>
<td width="100">最后登录IP：</td> 
<td><?php echo $lastloginip?></td>
</tr>

<tr>
<td width="100">真实姓名：</td>
<td>
<input type="text" name="info[realname]" id="realname" class="input-text" size="30" value="<?php echo $realname?>">
</td>
</tr>
</table>
</div>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
  </ul>
</div>
</form>
</div>
</div>