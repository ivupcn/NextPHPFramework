<div class="pageContent">
	<div class="pad_10">
		<form action="?m=oa&c=task&a=edit" method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="52">
			<table width="100%" class="table_form contentWrap">
				<tr>
					<th width="120">指派给：</th>
					<td>
						<select name="info[assignedto]" id="assignedto" class="required">
							{loop $user $r}
							<option value="{$r['userid']}"{if $info['assignedto'] == $r['userid']} selected{/if}>{$r['realname']}</option>
							{/loop}
						</select>
					</td>
				</tr>
				<tr>
					<th>任务名称：</th>
					<td>
						<input type="text" name="info[title]" class="required input-text" size="70" value="{$info['title']}" />
					</td>
				</tr>
				<tr>
					<th>计划开始时间：</th>
					<td>
						<input type="text" name="info[planstarttime]" value="{date('Y-m-d',$info['planstarttime'])}" class="required date" minDate="{date('Y-m-d',strtotime('-7 day'))}" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>计划结束时间：</th>
					<td>
						<input type="text" name="info[planendtime]" value="{date('Y-m-d',$info['planendtime'])}" class="required date" minDate="{date('Y-m-d',strtotime('-7 day'))}" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>任务类型：</th>
					<td>
						<?php echo form::select_category('category_oa_'.SITEID,$info['tagid'],'name="info[tagid]" id="tagid" class="required"','',0,-1);?>
					</td>
				</tr>
				<tr>
					<th>循环任务：</th>
					<td>
						<select name="info[looptask]" id="looptask" class="required">
							<option value="once"{if $info['looptask'] == 'once'} selected{/if}>一次性任务</option>
							<option value="everyday"{if $info['looptask'] == 'everyday'} selected{/if}>每天</option>
							<option value="weekly"{if $info['looptask'] == 'weekly'} selected{/if}>每周</option>
							<option value="Monday"{if $info['looptask'] == 'Monday'} selected{/if}>　├周一</option>
							<option value="Tuesday"{if $info['looptask'] == 'Tuesday'} selected{/if}>　├每二</option>
							<option value="Wednesday"{if $info['looptask'] == 'Wednesday'} selected{/if}>　├每三</option>
							<option value="Thursday"{if $info['looptask'] == 'Thursday'} selected{/if}>　├每四</option>
							<option value="Friday"{if $info['looptask'] == 'Friday'} selected{/if}>　├每五</option>
							<option value="Saturday"{if $info['looptask'] == 'Saturday'} selected{/if}>　├每六</option>
							<option value="Sunday"{if $info['looptask'] == 'Sunday'} selected{/if}>　└每日</option>
							<option value="permonth"{if $info['looptask'] == 'permonth'} selected{/if}>每月</option>
							<option value="1"{if $info['looptask'] == '1'} selected{/if}>　├1日</option>
							<option value="2"{if $info['looptask'] == '2'} selected{/if}>　├2日</option>
							<option value="3"{if $info['looptask'] == '3'} selected{/if}>　├3日</option>
							<option value="4"{if $info['looptask'] == '4'} selected{/if}>　├4日</option>
							<option value="5"{if $info['looptask'] == '5'} selected{/if}>　├5日</option>
							<option value="6"{if $info['looptask'] == '6'} selected{/if}>　├6日</option>
							<option value="7"{if $info['looptask'] == '7'} selected{/if}>　├7日</option>
							<option value="8"{if $info['looptask'] == '8'} selected{/if}>　├8日</option>
							<option value="9"{if $info['looptask'] == '9'} selected{/if}>　├9日</option>
							<option value="10"{if $info['looptask'] == '10'} selected{/if}>　├10日</option>
							<option value="11"{if $info['looptask'] == '11'} selected{/if}>　├11日</option>
							<option value="12"{if $info['looptask'] == '12'} selected{/if}>　├12日</option>
							<option value="13"{if $info['looptask'] == '13'} selected{/if}>　├13日</option>
							<option value="14"{if $info['looptask'] == '14'} selected{/if}>　├14日</option>
							<option value="15"{if $info['looptask'] == '15'} selected{/if}>　├15日</option>
							<option value="16"{if $info['looptask'] == '16'} selected{/if}>　├16日</option>
							<option value="17"{if $info['looptask'] == '17'} selected{/if}>　├17日</option>
							<option value="18"{if $info['looptask'] == '18'} selected{/if}>　├18日</option>
							<option value="19"{if $info['looptask'] == '19'} selected{/if}>　├19日</option>
							<option value="20"{if $info['looptask'] == '20'} selected{/if}>　├20日</option>
							<option value="21"{if $info['looptask'] == '21'} selected{/if}>　├21日</option>
							<option value="22"{if $info['looptask'] == '22'} selected{/if}>　├22日</option>
							<option value="23"{if $info['looptask'] == '23'} selected{/if}>　├23日</option>
							<option value="24"{if $info['looptask'] == '24'} selected{/if}>　├24日</option>
							<option value="25"{if $info['looptask'] == '25'} selected{/if}>　├25日</option>
							<option value="26"{if $info['looptask'] == '26'} selected{/if}>　├26日</option>
							<option value="27"{if $info['looptask'] == '27'} selected{/if}>　├27日</option>
							<option value="28"{if $info['looptask'] == '28'} selected{/if}>　├28日</option>
							<option value="29"{if $info['looptask'] == '29'} selected{/if}>　├29日</option>
							<option value="30"{if $info['looptask'] == '30'} selected{/if}>　├30日</option>
							<option value="31"{if $info['looptask'] == '31'} selected{/if}>　└31日</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>附件：</th>
					<td>
						<input id="FileInput" type="file" name="info[attachment]" value="{$info['attachment']}" uploaderOption="{formData:{swf_auth_key:'da646a36bf6c8936f06216c35b091f8e',SWFUPLOADSESSID:'1365091571',module:'admin',tagid:1,ajax:1},buttonText:'请选择文件',fileSizeLimit:'10240KB',fileTypeDesc:'*.jpg;*.jpeg;*.gif;*.png;',fileTypeExts:'*.jpg;*.jpeg;*.gif;*.png;',auto:true,multi:true,onUploadSuccess:uploadifySuccessThumb,onQueueComplete:uploadifyQueueComplete}" />
					</td>
				</tr>
				<tr>
					<th>任务描述：</th>
					<td>
						<textarea name="info[intro]" style="width:100%; height:150px">{$info['intro']}</textarea>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" value="{$id}">
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