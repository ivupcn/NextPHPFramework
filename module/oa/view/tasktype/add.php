<div class="pageContent">
  <form action="?m=oa&c=tasktype&a=add" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<fieldset>
			<legend>基本选项</legend>
		    <table width="100%" class="table_form contentWrap">
			 <tr>
			    <th width="100">添加方式：</th>
			    <td>
					<label><input type='radio' name='addtype' value='0' checked id="normal_addid" /> 单条添加</label>
					<label><input type='radio' name='addtype' value='1' id="batch_addid" onclick="$('#normal_add').html(' ');$('#normal_add').css('display','none');$('#batch_add').css('display','');$('#normal_addid').attr('disabled','true').removeAttr('checked');$('#batch_addid').attr('checked','true')" /> 批量添加</label>
				</td>
			</tr>
			<tr>
		        <th width="100">上级类型：</th>
		        <td>
				<?php echo form::select_category('category_oa_'.SITEID,$parentid,'name="info[parentid]" id="parentid"','≡ 作为一级类型 ≡',0,-1);?>
				</td>
		    </tr>
		    <tr>
		        <th width="100">类型名称：</th>
		        <td>
					<span id="normal_add"><input type="text" name="info[tagname]" id="tagname" class="required input-text" value="" /></span>
					<span id="batch_add" style="display:none"> 
				        <table width="100%" class="sss">
				        	<tr>
				        		<td width="260" style="padding-left:2px">
				        			<textarea name="batch_add" maxlength="255" style="width:250px;height:60px;"></textarea>
				        		</td>
						        <td align="left">
						        例如：<br /><font color="#959595">国内新闻|china<br />国际新闻|world</font><br />竖线以及后面的英文名可留空，默认会自动生成拼音
						 		</td>
				 			</tr>
				 		</table>
			        </span>
				</td>
		    </tr>
		    <tr>
		        <th width="100">描述：</th>
		        <td><textarea name="info[description]" maxlength="255" style="width:250px;height:60px;"></textarea></td>
		    </tr>
		    <tr>
		        <th width="100">工作流：</th>
		        <td>
		        	<?php
						$workflows = getcache('workflow_'.SITEID,'extend');
						if($workflows)
						{
							$workflows_datas = array();
							foreach($workflows as $_k=>$_v)
							{
								$workflows_datas[$_v['workflowid']] = $_v['workname'];
							}
							echo form::select($workflows_datas,'','name="setting[workflowid]"','不需要审核');
						}
						else
						{
							echo '<input type="hidden" name="setting[workflowid]" value="" />';
						}
					?>
		        </td>
		    </tr>
		    </table>
		</fieldset>
		<div class="bk10"></div>
		<fieldset>
			<legend>其他设置</legend>
		    <table width="100%" class="table_form contentWrap">
		    	<tr>
			        <th width="100">考评分值：</th>
			        <td><input name='setting[presentpoint]' type='text' value='1' size='5' maxlength='5' class="required number input-text" style='text-align:center' /></td>
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