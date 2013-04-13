<div class="pageContent">
<form action="{url 'user::add@user'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
<div class="pageFormContent" layoutH="52">
<table width="100%" class="table_form contentWrap">
<tr>
<td width="100">EMail</td>
<td>
<input type="text" name="info[email]" value="" class="input-text required email" id="email" />
</td>
</tr>
<tr>
<td>密码</td> 
<td><input type="password" name="info[password]" class="input-text required alphanumeric" minlength="6" maxlength="20" id="password" value="" /></td>
</tr>
<tr>
<td>确认密码</td> 
<td><input type="password" name="info[pwdconfirm]" class="input-text required" equalto="#password" id="pwdconfirm" value="" /></td>
</tr>
<tr>
<td>真实姓名</td>
<td>
<input type="text" name="info[realname]" value="" class="input-text required" id="realname" />
</td>
</tr>
<tr>
<td>所属角色</td>
<td>
	{loop $roles $key $val}
	{if $key != 1}
	<label><input type="checkbox" name="info[roleid][]" value="{$key}">{$val}</label>
	{/if}
	{/loop}
</td>
</tr>
<tr>
<td>所属用户组</td>
<td>
	<select name="info[groupid]" class="combox required">
		{loop $groups $key $val}
		<option value="{$key}">{$val['name']}</option>
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