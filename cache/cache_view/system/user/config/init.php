<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<form action="?m=user&c=config&a=init" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<div class="pageFormContent" layoutH="55">
			<table width="100%" class="table_form">
				<tr>
					<th width="200">允许新会员注册：</th>
					<td class="y-bg">
						<label>是<input type="radio" name="info[allowregister]"  class="input-radio" <?php if($user_config['allowregister']) {?>checked<?php }?> value='1' /></label>
						<label>否<input type="radio" name="info[allowregister]"  class="input-radio" <?php if(!$user_config['allowregister']) {?>checked<?php }?> value='0' /></label>
					</td>
				</tr>
				<tr>
					<th>1元人民币购买积分数量：</th>
					<td class="y-bg"><input type="text" name="info[rmb_point_rate]" id="rmb_point_rate" class="input-text digits" size="24" value="<?php echo $user_config['rmb_point_rate'];?>" alt="请输入1元人民币购买积分数量" /></td>
				</tr>
				<tr>
					<th>新会员默认点数：</th>
					<td class="y-bg"><input type="text" name="info[defualtpoint]" id="defualtpoint" class="input-text digits" size="24" value="<?php echo $user_config['defualtpoint'];?>" alt="请输入新会员默认点数" /></td>
				</tr>
				<tr>
					<th>新注册会员默认用户组：</th>
					<td class="y-bg">
						<select name="info[defaultgroupid]" id="defaultgroupid" class="required combox">
							<?php if(is_array($grouplist)) foreach($grouplist AS $key => $val) { ?>
							<option value="<?php echo $val['groupid'];?>"<?php if($user_config['defaultgroupid'] == $val['groupid']) { ?> selected<?php } ?>><?php echo $val['name'];?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>会员注册协议：</th>
					<td class="y-bg">
						<textarea class="editor" name="info[regprotocol]" style="width:80%; height:120px"
		                tools="Source,|,Cut,Copy,Paste,Pastetext,|,Bold,Italic,Underline,Strikethrough,|,FontColor,BackColor,Removeformat,|,Align,List"
		                upLinkUrl="upload.php" upLinkExt="zip,rar,txt" 
		                upImgUrl="upload.php" upImgExt="jpg,jpeg,gif,png" 
		                upFlashUrl="upload.php" upFlashExt="swf"
		                upMediaUrl="upload.php" upMediaExt:"avi"><?php echo $user_config['regprotocol']?></textarea>
					</td>
				</tr>
				<tr>
					<th>邮件认证内容：</th>
					<td class="y-bg">
						<textarea name="info[registerverifymessage]" id="registerverifymessage" style="width:80%;height:120px;float:none"><?php echo $user_config['registerverifymessage']?></textarea>
						<br />可用变量：Email - {email}，密码 - {password}  ，点击认证地址 - {click} ，链接地址：{url}
					</td>
				</tr>
				<tr>
					<th>密码找回邮件内容：</th>
					<td class="y-bg">
						<textarea name="info[forgetpassword]" id="forgetpassword" style="width:80%;height:120px;"><?php echo $user_config['forgetpassword']?></textarea>
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