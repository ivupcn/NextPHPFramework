<div class="pageContent">
<form action="?m=user&c=user&a=edit" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
<div class="pageFormContent" layoutH="52">
<input type="hidden" name="info[userid]" value="<?php echo $userid?>" />
<input type="hidden" name="info[email]" value="<?php echo $email?>" />
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80">EMail</td> 
<td>{$email}</td>
</tr>
<tr>
<td>密码</td> 
<td><input type="password" name="info[password]" id="password" class="input-text alphanumeric" /></td>
</tr>
<tr>
<td>确认密码</td> 
<td><input type="password" name="info[pwdconfirm]" id="pwdconfirm" class="input-text" equalto="#password" /></td>
</tr>
<tr>
<td>真实姓名</td>
<td>
<input type="text" name="info[realname]" value="{$realname}" class="input-text" id="realname" />
</td>
</tr>
<tr>
<td>所属角色</td>
<td>
	{php $roleid_arr = normalize($roleid)}
	{loop $roles $key $val}
	{if $key != 1}
	<label><input type="checkbox" name="info[roleid][]" value="{$key}" {if in_array($key,$roleid_arr)}checked{/if} />{$val}</label>
	{/if}
	{/loop}
</td>
</tr>
<tr>
<td>所属用户组</td>
<td>
	<select name="info[groupid]" class="combox required">
		{loop $groups $key $val}
		<option value="{$key}"{if $groupid == $key} selected{/if}>{$val['name']}</option>
		{/loop}
	</select>
</td>
</tr>
</table>
</div>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</form>
</div>