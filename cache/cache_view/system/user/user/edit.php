<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
<form action="?m=user&c=user&a=edit" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
<div class="pageFormContent" layoutH="52">
<input type="hidden" name="info[userid]" value="<?php echo $userid?>" />
<input type="hidden" name="info[email]" value="<?php echo $email?>" />
<table width="100%" class="table_form contentWrap">
<tr>
<td width="80">EMail</td> 
<td><?php echo $email;?></td>
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
<input type="text" name="info[realname]" value="<?php echo $realname;?>" class="input-text" id="realname" />
</td>
</tr>
<tr>
<td>所属角色</td>
<td>
	<?php $roleid_arr = normalize($roleid)?>
	<?php if(is_array($roles)) foreach($roles AS $key => $val) { ?>
	<?php if($key != 1) { ?>
	<label><input type="checkbox" name="info[roleid][]" value="<?php echo $key;?>" <?php if(in_array($key,$roleid_arr)) { ?>checked<?php } ?> /><?php echo $val;?></label>
	<?php } ?>
	<?php } ?>
</td>
</tr>
<tr>
<td>所属用户组</td>
<td>
	<select name="info[groupid]" class="combox required">
		<?php if(is_array($groups)) foreach($groups AS $key => $val) { ?>
		<option value="<?php echo $key;?>"<?php if($groupid == $key) { ?> selected<?php } ?>><?php echo $val['name'];?></option>
		<?php } ?>
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