<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
<form action="?m=user&c=role&a=edit" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
<div class="pageFormContent" layoutH="52">
<table width="100%" class="table_form contentWrap">
<tr>
<td>角色名</td> 
<td><input type="text" name="info[rolename]" value="<?php echo $rolename;?>" class="input-text" id="rolename"></td>
</tr>
<tr>
<td>角色描述</td>
<td><textarea name="info[description]" rows="2" cols="20" id="description" class="inputtext" style="height:100px;width:380px;"><?php echo $description;?></textarea></td>
</tr>
<tr>
<td>是否启用</td>
<td><label><input type="radio" name="info[disabled]" value="0" <?php if($disabled==0) { ?>checked<?php } ?>>启用</label>  <label><input type="radio" name="info[disabled]" value="1" <?php if($disabled==1) { ?>checked<?php } ?>>禁止</label></td>
</tr>
</table>
<input type="hidden" name="roleid" value="<?php echo $roleid;?>">
</div>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</form>
</div>