<div class="pageContent">
  <form action="?m=oa&c=tasktype&a=edit" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<fieldset>
			<legend>基本选项</legend>
		    <table width="100%" class="table_form contentWrap">
			<tr>
		        <th width="100">上级类型：</th>
		        <td>
				<?php echo form::select_category('category_oa_'.$this->get_siteid(),$info['parentid'],'name="info[parentid]" id="parentid"','≡ 作为一级类型 ≡',0,-1);?>
				</td>
		    </tr>
		    <tr>
		        <th width="100">类型名称：</th>
		        <td><input type="text" name="info[tagname]" id="tagname" class="required input-text" value="{$info['tagname']}" /></td>
		    </tr>
		    <tr>
		        <th width="100">描述：</th>
		        <td><textarea name="info[description]" maxlength="255" style="width:250px;height:60px;">{$info['description']}</textarea></td>
		    </tr>
		    <tr>
		        <th width="100">工作流：</th>
		        <td>
		        	<?php
						$workflows = getcache('workflow_'.$this->get_siteid(),'extend');
						if($workflows)
						{
							$workflows_datas = array();
							foreach($workflows as $_k=>$_v)
							{
								$workflows_datas[$_v['workflowid']] = $_v['workname'];
							}
							echo form::select($workflows_datas,$setting['workflowid'],'name="setting[workflowid]"','不需要审核');
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
			        <td><input name='setting[presentpoint]' type='text' value='{$setting['presentpoint']}' size='5' maxlength='5' class="required number input-text" style='text-align:center' /></td>
		    	</tr>
		    </table>
		</fieldset>
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