<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<h2 class="contentTitle">修改密码</h2>
<form action="<?php echo $this->_context->url('user::editPwd@user'); ?>" method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
	<div class="pageFormContent" layoutH="95">
<input type="hidden" name="info[userid]" value="<?php echo $userid?>">
<input type="hidden" name="info[email]" value="<?php echo $email?>">
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80">Email</td> 
<td><?php echo $email?></td>
</tr>

<tr>
<td>真实姓名</td>
<td>
<?php echo $realname?>
</td>
</tr>

<tr>
<td>旧密码</td> 
<td><input type="password" name="old_password" id="old_password" class="input-text required alphanumeric" /></td>
</tr>

<tr>
<td>新密码</td> 
<td><input type="password" name="new_password" id="new_password" class="input-text required alphanumeric" minlength="8" maxlength="20" /></td>
</tr>
<tr>
<td>重复新密码</td> 
<td><input type="password" name="new_pwdconfirm" id="new_pwdconfirm" class="input-text" equalto="#new_password" /></td>
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