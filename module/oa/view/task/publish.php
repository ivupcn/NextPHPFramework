<div class="pageContent">
	<div class="pad_10">
		<form action="?m=oa&c=task&a=publish" method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<div class="pageFormContent" layoutH="52">
			<table width="100%" class="table_form contentWrap">
				<tr>
				    <th width="120">指派方式：</th>
				    <td>
						<label><input type='radio' name='addtype' value='0' checked id="normal_addid" onclick="$('#assignedto').removeAttr('multiple').removeAttr('size').attr('name','info[assignedto]');$('#normal_addid').attr('checked','true')" />指派一人</label>
						<label><input type='radio' name='addtype' value='1' id="batch_addid" onclick="$('#assignedto').attr({'multiple':'true','size':7,'name':'assignedto[]'});$('#batch_addid').attr('checked','true')" />指派多人</label>
					</td>
				</tr>
				<tr>
					<th>指派给：</th>
					<td>
						<select name="info[assignedto]" id="assignedto" class="required">
							{loop $user $r}
							<option value="{$r['userid']}"{if $_SESSION['userid'] == $r['userid']} selected{/if}>{$r['realname']}</option>
							{/loop}
						</select>
					</td>
				</tr>
				<tr>
					<th>任务名称：</th>
					<td>
						<input type="text" name="info[title]" class="required input-text" size="70" />
					</td>
				</tr>
				<tr>
					<th>计划开始时间：</th>
					<td>
						<input type="text" name="info[planstarttime]" value="{date('Y-m-d')}" class="required date" minDate="{date('Y-m-d',strtotime('-7 day'))}" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>计划结束时间：</th>
					<td>
						<input type="text" name="info[planendtime]" value="{date('Y-m-d')}" class="required date" minDate="{date('Y-m-d',strtotime('-7 day'))}" readonly="true" />
					</td>
				</tr>
				<tr>
					<th>任务类型：</th>
					<td>
						<?php echo form::select_category('category_oa_'.SITEID,0,'name="info[tagid]" id="tagid" class="required"','',0,-1);?>
					</td>
				</tr>
				<tr>
					<th>循环任务：</th>
					<td>
						<select name="info[looptask]" id="looptask" class="required">
							<option value="once">一次性任务</option>
							<option value="everyday">每天</option>
							<option value="weekly">每周</option>
							<option value="Monday">　├周一</option>
							<option value="Tuesday">　├每二</option>
							<option value="Wednesday">　├每三</option>
							<option value="Thursday">　├每四</option>
							<option value="Friday">　├每五</option>
							<option value="Saturday">　├每六</option>
							<option value="Sunday">　└每日</option>
							<option value="permonth">每月</option>
							<option value="1">　├1日</option>
							<option value="2">　├2日</option>
							<option value="3">　├3日</option>
							<option value="4">　├4日</option>
							<option value="5">　├5日</option>
							<option value="6">　├6日</option>
							<option value="7">　├7日</option>
							<option value="8">　├8日</option>
							<option value="9">　├9日</option>
							<option value="10">　├10日</option>
							<option value="11">　├11日</option>
							<option value="12">　├12日</option>
							<option value="13">　├13日</option>
							<option value="14">　├14日</option>
							<option value="15">　├15日</option>
							<option value="16">　├16日</option>
							<option value="17">　├17日</option>
							<option value="18">　├18日</option>
							<option value="19">　├19日</option>
							<option value="20">　├20日</option>
							<option value="21">　├21日</option>
							<option value="22">　├22日</option>
							<option value="23">　├23日</option>
							<option value="24">　├24日</option>
							<option value="25">　├25日</option>
							<option value="26">　├26日</option>
							<option value="27">　├27日</option>
							<option value="28">　├28日</option>
							<option value="29">　├29日</option>
							<option value="30">　├30日</option>
							<option value="31">　└31日</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>附件：</th>
					<td>
						<input id="publishatt" type="file" name="info[attachment]" value="rw" uploaderOption="{formData:{swf_auth_key:'da646a36bf6c8936f06216c35b091f8e',SWFUPLOADSESSID:'1365091571',module:'admin',tagid:1,ajax:1},buttonText:'请选择文件',fileSizeLimit:'10240KB',fileTypeDesc:'*.jpg;*.jpeg;*.gif;*.png;*.doc;*.docx;*.xls;*.xlsx;*.txt;*.psd;*.pdf;',fileTypeExts:'.jpg;*.jpeg;*.gif;*.png;*.doc;*.docx;*.xls;*.xlsx;*.txt;*.psd;*.pdf;',auto:true,multi:true,onUploadSuccess:uploadifySuccessInput,onQueueComplete:uploadifyQueueComplete}" />
					</td>
				</tr>
				<tr>
					<th>任务描述：</th>
					<td>
						<textarea name="info[intro]" style="width:100%; height:150px"></textarea>
					</td>
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
</div>