<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div class="pad_10">
		<form action="?m=oa&c=task&a=edit" method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="52">
			<table width="100%" class="table_form contentWrap">
				<tr>
					<th width="120">指派给：</th>
					<td>
						<select name="info[assignedto]" id="assignedto" class="required">
							<?php if(is_array($user)) foreach($user AS $r) { ?>
							<option value="<?php echo $r['userid'];?>"<?php if($info['assignedto'] == $r['userid']) { ?> selected<?php } ?>><?php echo $r['realname'];?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>任务名称：</th>
					<td>
						<input type="text" name="info[title]" class="required input-text" size="70" value="<?php echo $info['title'];?>" />
					</td>
				</tr>
				<tr>
					<th>计划开始时间：</th>
					<td>
						<input type="text" name="info[planstarttime]" value="<?php echo date('Y-m-d',$info['planstarttime']);?>" class="required date" minDate="<?php echo date('Y-m-d',strtotime('-7 day'));?>" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>计划结束时间：</th>
					<td>
						<input type="text" name="info[planendtime]" value="<?php echo date('Y-m-d',$info['planendtime']);?>" class="required date" minDate="<?php echo date('Y-m-d',strtotime('-7 day'));?>" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>任务类型：</th>
					<td>
						<?php echo form::select_category('category_oa_'.$this->get_siteid(),$info['tagid'],'name="info[tagid]" id="tagid" class="required"','',0,-1);?>
					</td>
				</tr>
				<tr>
					<th>循环任务：</th>
					<td>
						<select name="info[looptask]" id="looptask" class="required">
							<option value="once"<?php if($info['looptask'] == 'once') { ?> selected<?php } ?>>一次性任务</option>
							<option value="everyday"<?php if($info['looptask'] == 'everyday') { ?> selected<?php } ?>>每天</option>
							<option value="weekly"<?php if($info['looptask'] == 'weekly') { ?> selected<?php } ?>>每周</option>
							<option value="Monday"<?php if($info['looptask'] == 'Monday') { ?> selected<?php } ?>>　├周一</option>
							<option value="Tuesday"<?php if($info['looptask'] == 'Tuesday') { ?> selected<?php } ?>>　├每二</option>
							<option value="Wednesday"<?php if($info['looptask'] == 'Wednesday') { ?> selected<?php } ?>>　├每三</option>
							<option value="Thursday"<?php if($info['looptask'] == 'Thursday') { ?> selected<?php } ?>>　├每四</option>
							<option value="Friday"<?php if($info['looptask'] == 'Friday') { ?> selected<?php } ?>>　├每五</option>
							<option value="Saturday"<?php if($info['looptask'] == 'Saturday') { ?> selected<?php } ?>>　├每六</option>
							<option value="Sunday"<?php if($info['looptask'] == 'Sunday') { ?> selected<?php } ?>>　└每日</option>
							<option value="permonth"<?php if($info['looptask'] == 'permonth') { ?> selected<?php } ?>>每月</option>
							<option value="1"<?php if($info['looptask'] == '1') { ?> selected<?php } ?>>　├1日</option>
							<option value="2"<?php if($info['looptask'] == '2') { ?> selected<?php } ?>>　├2日</option>
							<option value="3"<?php if($info['looptask'] == '3') { ?> selected<?php } ?>>　├3日</option>
							<option value="4"<?php if($info['looptask'] == '4') { ?> selected<?php } ?>>　├4日</option>
							<option value="5"<?php if($info['looptask'] == '5') { ?> selected<?php } ?>>　├5日</option>
							<option value="6"<?php if($info['looptask'] == '6') { ?> selected<?php } ?>>　├6日</option>
							<option value="7"<?php if($info['looptask'] == '7') { ?> selected<?php } ?>>　├7日</option>
							<option value="8"<?php if($info['looptask'] == '8') { ?> selected<?php } ?>>　├8日</option>
							<option value="9"<?php if($info['looptask'] == '9') { ?> selected<?php } ?>>　├9日</option>
							<option value="10"<?php if($info['looptask'] == '10') { ?> selected<?php } ?>>　├10日</option>
							<option value="11"<?php if($info['looptask'] == '11') { ?> selected<?php } ?>>　├11日</option>
							<option value="12"<?php if($info['looptask'] == '12') { ?> selected<?php } ?>>　├12日</option>
							<option value="13"<?php if($info['looptask'] == '13') { ?> selected<?php } ?>>　├13日</option>
							<option value="14"<?php if($info['looptask'] == '14') { ?> selected<?php } ?>>　├14日</option>
							<option value="15"<?php if($info['looptask'] == '15') { ?> selected<?php } ?>>　├15日</option>
							<option value="16"<?php if($info['looptask'] == '16') { ?> selected<?php } ?>>　├16日</option>
							<option value="17"<?php if($info['looptask'] == '17') { ?> selected<?php } ?>>　├17日</option>
							<option value="18"<?php if($info['looptask'] == '18') { ?> selected<?php } ?>>　├18日</option>
							<option value="19"<?php if($info['looptask'] == '19') { ?> selected<?php } ?>>　├19日</option>
							<option value="20"<?php if($info['looptask'] == '20') { ?> selected<?php } ?>>　├20日</option>
							<option value="21"<?php if($info['looptask'] == '21') { ?> selected<?php } ?>>　├21日</option>
							<option value="22"<?php if($info['looptask'] == '22') { ?> selected<?php } ?>>　├22日</option>
							<option value="23"<?php if($info['looptask'] == '23') { ?> selected<?php } ?>>　├23日</option>
							<option value="24"<?php if($info['looptask'] == '24') { ?> selected<?php } ?>>　├24日</option>
							<option value="25"<?php if($info['looptask'] == '25') { ?> selected<?php } ?>>　├25日</option>
							<option value="26"<?php if($info['looptask'] == '26') { ?> selected<?php } ?>>　├26日</option>
							<option value="27"<?php if($info['looptask'] == '27') { ?> selected<?php } ?>>　├27日</option>
							<option value="28"<?php if($info['looptask'] == '28') { ?> selected<?php } ?>>　├28日</option>
							<option value="29"<?php if($info['looptask'] == '29') { ?> selected<?php } ?>>　├29日</option>
							<option value="30"<?php if($info['looptask'] == '30') { ?> selected<?php } ?>>　├30日</option>
							<option value="31"<?php if($info['looptask'] == '31') { ?> selected<?php } ?>>　└31日</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>附件：</th>
					<td>
						<input id="FileInput" type="file" name="info[attachment]" value="<?php echo $info['attachment'];?>" uploaderOption="{formData:{swf_auth_key:'da646a36bf6c8936f06216c35b091f8e',SWFUPLOADSESSID:'1365091571',module:'admin',tagid:1,ajax:1},buttonText:'请选择文件',fileSizeLimit:'10240KB',fileTypeDesc:'*.jpg;*.jpeg;*.gif;*.png;',fileTypeExts:'*.jpg;*.jpeg;*.gif;*.png;',auto:true,multi:true,onUploadSuccess:uploadifySuccessThumb,onQueueComplete:uploadifyQueueComplete}" />
					</td>
				</tr>
				<tr>
					<th>任务描述：</th>
					<td>
						<textarea name="info[intro]" style="width:100%; height:150px"><?php echo $info['intro'];?></textarea>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo $id;?>">
		</div>
		<div class="formBar">
	      <ul>
	        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
	        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
	      </ul>
	    </div>
		</form>
	</div>
</div>