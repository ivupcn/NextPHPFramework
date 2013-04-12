<div class="pageContent">
  <form action="?m=oa&c=task&a=complete" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<fieldset>
			<legend>任务进度</legend>
			<table width="100%" class="table_form contentWrap">
	    		{nloop $progress $r}
	    		<tr class="progressbox">
	    			<td>
	    				<strong>{$n}#</strong><br /><br />
	    				<input type="button" onclick="addFile(this)" class="icon-add" value="&nbsp;" />
	    				<input type="button" onclick="delFile(this)" class="icon-delete" value="&nbsp;" />
	    			</td>
	    			<td>
	    				<label>标题：<input type="text" name="progress[{$n}][title]" class="input-text" value="{$r['title']}" size="50" /></label><br />
	    				<label> URL：<input type="text" name="progress[{$n}][url]" class="input-text" value="{$r['url']}" size="50" /></label>
	    			</td>
	    			<td>
	    				备注：
	    				<textarea name="progress[{$n}][description]" maxlength="255" style="width:350px;height:65px;">{$r['description']}</textarea>
	    			</td>
	    		</tr>
	    		{/nloop}
	    		{if empty($progress)}
	    		<tr class="progressbox">
	    			<td>
	    				<strong>1#</strong><br /><br />
	    				<input type="button" onclick="addFile(this)" class="icon-add" value="&nbsp;" />
	    				<input type="button" onclick="delFile(this)" class="icon-delete" value="&nbsp;" />
	    			</td>
	    			<td>
	    				<label>标题：<input type="text" name="progress[1][title]" class="input-text" value="" size="50" /></label><br />
	    				<label> URL：<input type="text" name="progress[1][url]" class="input-text" value="" size="50" /></label>
	    			</td>
	    			<td>
	    				备注：
	    				<textarea name="progress[1][description]" maxlength="255" style="width:350px;height:65px;"></textarea>
	    			</td>
	    		</tr>
	    		{/if}
	    	</table>
	    	<script language='javascript'>
			function addFile(clickedButton)
			{
			    fileRow = '<tr class="progressbox"><td><strong>#i#</strong><br /><br /><input type="button" onclick="addFile(this)" class="icon-add" value="&nbsp;" /><input type="button" onclick="delFile(this)" class="icon-delete" value="&nbsp;" /></td><td><label>标题：<br /><input type="text" name="progress[#i][title]" class="input-text" value="" size="50" /></label><br /><label> URL：<br /><input type="text" name="progress[#i][url]" class="input-text" value="" size="50" /></label></td><td>备注：<br /><textarea name="progress[#i][description]" maxlength="255" style="width:350px;height:65px;"></textarea></td></tr>';
			    fileRow = fileRow.replace(new RegExp('#i','gm'),$('.progressbox').size() + 1);
			    $(clickedButton).parent().parent().after(fileRow);
			}
			function delFile(clickedButton)
			{
			    if($('.progressbox').size() == 1) return;
			    $(clickedButton).parent().parent().remove();
			} 
			</script>
		</fieldset>
		<div class="bk10"></div>
		<fieldset>
			<legend>汇报任务</legend>
			<table width="100%" class="table_form contentWrap">
				<tr>
					<th width="80">任务名称：</th>
					<td>{$info['title']}</td>
				</tr>
				<tr>
					<th>任务类型：</th>
					<td>{$category[$info['tagid']]['tagname']}</td>
				</tr>
				<tr>
					<th>完成时间：</th>
					<td>{date('Y-m-d H:i:s',SYS_TIME)}</td>
				</tr>
				<tr>
					<th>任务分值：</th>
					<td>
						{php $setting = string2array($category[$info['tagid']]['setting'])}
          				{$setting['presentpoint']}
					</td>
				</tr>
				<tr>
					<th>自评分值：</th>
					<td>
						{php $setting = string2array($category[$info['tagid']]['setting'])}
          				<input type="text" name="info[selfrated]" class="required input-text" value="{$setting['presentpoint']}" size="3" />
					</td>
				</tr>
				<tr>
					<th>附件：</th>
					<td>
          				<input id="completeatt" type="file" name="info[attachment]" value="" uploaderOption="{formData:{swf_auth_key:'da646a36bf6c8936f06216c35b091f8e',SWFUPLOADSESSID:'1365091571',module:'admin',tagid:1,ajax:1},buttonText:'请选择文件',fileSizeLimit:'10240KB',fileTypeDesc:'.jpg;*.jpeg;*.gif;*.png;*.doc;*.docx;*.xls;*.xlsx;*.txt;*.psd;*.pdf;',fileTypeExts:'.jpg;*.jpeg;*.gif;*.png;*.doc;*.docx;*.xls;*.xlsx;*.txt;*.psd;*.pdf;',auto:true,multi:true,onUploadSuccess:uploadifySuccessInput,onQueueComplete:uploadifyQueueComplete}" />
					</td>
				</tr>
				<tr>
					<th>备注：</th>
					<td>
          				<textarea name="info[report]" maxlength="255" style="width:100%;height:65px;"> </textarea>
					</td>
				</tr>
			</table>
		</fieldset>
    </div>
    <input type="hidden" name="id" value="{$id}">
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>