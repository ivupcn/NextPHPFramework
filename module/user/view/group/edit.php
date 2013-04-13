<div class="pageContent">
<form action="{url 'group::edit@user'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
<div class="pageFormContent" layoutH="52">
<input type="hidden" name="info[groupid]"value="<?php echo $groupinfo['groupid']?>">
<fieldset>
	<legend>基本信息</legend>
	<table width="100%" class="table_form">
		<tr>
			<td width="100">会员组名称：</td> 
			<td><input type="text" name="info[name]" id="name" value="<?php echo $groupinfo['name']?>" class="input-text required" /></td>
		</tr>
		<tr>
			<td>积分小于：</td> 
			<td>
			<input type="text" name="info[point]" id="group_point" value="<?php echo $groupinfo['point']?>" size="6" class="input-text required digits" /></td>
		</tr>
		<tr>
			<td>星星数：</td> 
			<td><input type="text" name="info[starnum]" id="group_starnum" value="<?php echo $groupinfo['starnum']?>" size="6" class="input-text required digits" /></td>
		</tr>
	</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
	<legend>详细信息</legend>
	<table width="100%" class="table_form">
		<tr>
			<td>用户权限：</td> 
			<td>
				<label><input type="checkbox" name="info[allowvisit]" <?php if($groupinfo['allowvisit']){?>checked<?php }?> />允许访问</label>
				<label><input type="checkbox" name="info[allowpost]" <?php if($groupinfo['allowpost']){?>checked<?php }?> />允许投稿</label>
				<label><input type="checkbox" name="info[allowpostverify]" <?php if($groupinfo['allowpostverify']){?>checked<?php }?>>投稿不需审核</label>
				<label><input type="checkbox" name="info[allowupgrade]" <?php if($groupinfo['allowupgrade']){?>checked<?php }?> />允许自助升级</label>
				<label><input type="checkbox" name="info[allowsendmessage]" <?php if($groupinfo['allowsendmessage']){?>checked<?php }?>>允许发短消息</label>
				<label><input type="checkbox" name="info[allowattachment]" <?php if($groupinfo['allowattachment']){?>checked<?php }?>>允许上传附件</label>
				<label><input type="checkbox" name="info[allowsearch]" <?php if($groupinfo['allowsearch']){?>checked<?php }?>>搜索权限</label>
				<label><input type="checkbox" name="info[spamcertification]" <?php if($groupinfo['spamcertification']){?>checked<?php }?>>邮件认证</label>
			</td>

		</tr>

		<tr>
			<td width="100">升级价格：</td> 
			<td>
				<label><span class="left">包日：</span><input type="text" name="info[price_d]" class="input-text" value="<?php echo $groupinfo['price_d']?>" size="6" /></label>
				<label><span class="left">包月：</span><input type="text" name="info[price_m]" class="input-text" value="<?php echo $groupinfo['price_m']?>" size="6" /></label>
				<label><span class="left">包年：</span><input type="text" name="info[price_y]" class="input-text" value="<?php echo $groupinfo['price_y']?>" size="6" /></label>
			</td>
		</tr>
		<tr>
			<td>最大短消息数：</td> 
			<td><input type="text" name="info[allowmessage]" id="maxmessagenum" value="<?php echo $groupinfo['allowmessage']?>" size="8" class="input-text digits" /></td>
		</tr>
		<tr>
			<td>日最大投稿数：</td> 
			<td><input type="text" name="info[allowpostnum]" id="allowpostnum" size="8" value="<?php echo $groupinfo['allowpostnum']?>" class="input-text digits" /><span class="info">0为不限制</span></td>
		</tr>
		<tr>
			<td>用户名颜色：</td> 
			<td><input type="text" name="info[usernamecolor]" id="usernamecolor" size="8" value="<?php echo $groupinfo['usernamecolor']?>" class="input-text" /></td>
		</tr>
		<tr>
			<td>用户组图标：</td> 
			<td><input type="text" name="info[icon]" id="icon" value="<?php echo $groupinfo['icon']?>" size="40" class="input-text" /></td>
		</tr>
		<tr>
			<td>简洁描述：</td> 
			<td><input type="text" name="info[description]" value="<?php echo $groupinfo['description']?>" size="60" class="input-text" /></td>
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