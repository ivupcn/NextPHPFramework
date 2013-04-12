<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
<form action="?m=user&c=group&a=add" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
	<div class="pageFormContent" layoutH="52">
<fieldset>
	<legend>基本信息</legend>
	<table width="100%" class="table_form">
		<tr>
			<td width="100">会员组名称：</td> 
			<td><input type="text" name="info[name]" id="name" class="input-text required" /></td>
		</tr>
		<tr>
			<td>积分小于：</td> 
			<td>
			<input type="text" name="info[point]" id="group_point" value="" size="6" class="input-text required digits" /></td>
		</tr>
		<tr>
			<td>星星数：</td> 
			<td><input type="text" name="info[starnum]" id="group_starnum" size="6" class="input-text required digits" /></td>
		</tr>
	</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
	<legend>详细信息：</legend>
	<table width="100%" class="table_form">
		<tr>
			<td>用户权限：</td> 
			<td>
					<label><input type="checkbox" name="info[allowvisit]" />允许访问</label>
					<label><input type="checkbox" name="info[allowpost]" />允许投稿</label>
					<label><input type="checkbox" name="info[allowpostverify]" />投稿不需审核</label>
					<label><input type="checkbox" name="info[allowupgrade]" />允许自助升级</label>
					<label><input type="checkbox" name="info[allowsendmessage]" />允许发短消息</label>
					<label><input type="checkbox" name="info[allowattachment]" />允许上传附件</label>
					<label><input type="checkbox" name="info[allowsearch]" />搜索权限</label>
					<label><input type="checkbox" name="info[spamcertification]" />邮件认证</label>
			</td>

		</tr>

		<tr>
			<td width="100">升级价格：</td> 
			<td>
					<label><span class="left">包日：</span><input type="text" name="info[price_d]" size="6" class="input-text number" /></label>
					<label><span class="left">包月：</span><input type="text" name="info[price_m]" size="6" class="input-text number" /></label>
					<label><span class="left">包年：</span><input type="text" name="info[price_y]" size="6" class="input-text number" /></label>
			</td>
		</tr>
		<tr>
			<td>最大短消息数：</td> 
			<td><input type="text" name="info[allowmessage]" id="allowmessage" size="8" class="input-text digits" /></td>
		</tr>
		<tr>
			<td>日最大投稿数：</td> 
			<td><input type="text" name="info[allowpostnum]" id="allowpostnum" size="8" class="input-text digits" /><span class="info">0为不限制</span></td>
		</tr>
		<tr>
			<td>用户名颜色：</td> 
			<td><input type="text" name="info[usernamecolor]" id="usernamecolor" size="8" value="#000000" class="input-text" /></td>
		</tr>
		<tr>
			<td>用户组图标：</td> 
			<td><input type="text" name="info[icon]" id="icon" value="images/group/vip.jpg" size="40" class="input-text" /></td>
		</tr>
		<tr>
			<td>简洁描述：</td> 
			<td><input type="text" name="info[description]" size="60" class="input-text" /></td>
		</tr>
	</table>
</fieldset>
</div>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</form>
</div>