<div class="pageContent">
  <form action="?m=oa&c=task&a=progress" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<table width="100%" class="table_form contentWrap">
    		{nloop $progress $r}
    		<tr class="progressbox">
    			<td>
    				<strong>{$n}#</strong><br /><br />
    				<input type="button" onclick="addFile(this)" class="icon-add" value="&nbsp;" />
    				<input type="button" onclick="delFile(this)" class="icon-delete" value="&nbsp;" />
    			</td>
    			<td>
    				<label>标题：<input type="text" name="info[{$n}][title]" class="input-text" value="{$r['title']}" size="50" /></label><br />
    				<label> URL：<input type="text" name="info[{$n}][url]" class="input-text" value="{$r['url']}" size="50" /></label>
    			</td>
    			<td>
    				备注：
    				<textarea name="info[{$n}][description]" maxlength="255" style="width:350px;height:65px;">{$r['description']}</textarea>
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
    				<label>标题：<input type="text" name="info[1][title]" class="input-text" value=" " size="50" /></label><br />
    				<label> URL：<input type="text" name="info[1][url]" class="input-text" value=" " size="50" /></label>
    			</td>
    			<td>
    				备注：
    				<textarea name="info[1][description]" maxlength="255" style="width:350px;height:65px;"> </textarea>
    			</td>
    		</tr>
    		{/if}
    	</table>
    	<input type="hidden" name="id" value="{$id}">
    </div>
    <script language='javascript'>
	function addFile(clickedButton)
	{
	    fileRow = '<tr class="progressbox"><td><strong>#i#</strong><br /><br /><input type="button" onclick="addFile(this)" class="icon-add" value="&nbsp;" /><input type="button" onclick="delFile(this)" class="icon-delete" value="&nbsp;" /></td><td><label>标题：<br /><input type="text" name="info[#i][title]" class="input-text" value="" size="50" /></label><br /><label> URL：<br /><input type="text" name="info[#i][url]" class="input-text" value="" size="50" /></label></td><td>备注：<br /><textarea name="info[#i][description]" maxlength="255" style="width:350px;height:65px;"></textarea></td></tr>';
	    fileRow = fileRow.replace(new RegExp('#i','gm'),$('.progressbox').size() + 1);
	    $(clickedButton).parent().parent().after(fileRow);
	}
	function delFile(clickedButton)
	{
	    if($('.progressbox').size() == 1) return;
	    $(clickedButton).parent().parent().remove();
	} 
	</script>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>