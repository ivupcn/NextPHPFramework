<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<form action="<?php echo $this->_context->url('category::add@content'); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="52">
			<fieldset>
				<legend>基本选项</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">上级栏目：</th>
						<td>
							<?php echo form::select_category('category_content_'.SITEID,$parentid,'name="info[parentid]"','≡ 作为一级栏目 ≡',0,-1);?>
						</td>
					</tr>
					<tr>
						<th>栏目名称：</th>
						<td>
							<span id="normal_add"><input type="text" name="info[catname]" class="input-text required" value="" /></span>
						</td>
					</tr>
					<tr>
						<th>英文目录：</th>
						<td><input type="text" name="info[catdir]" class="input-text required" value=""></td>
					</tr>
					<tr>
						<th>栏目图片：</th>
						<td><?php echo form::images('info[image]', 'image', '', 'content');?></td>
					</tr>
					<tr>
						<th>选择模型：</th>
						<td>
							<table>
								<?php $n=1;if(is_array($models)) foreach($models AS $model) { ?>
								<tr>
									<td><input type="checkbox" name="info[model][<?php echo $n;?>][enable]" value="1" checked /></td>
									<td><input type="hidden" name="info[model][<?php echo $n;?>][modelid]" value="<?php echo $model['modelid'];?>" /><input type="hidden" name="info[model][<?php echo $n;?>][name]" value="<?php echo $model['name'];?>" /><?php echo $model['name'];?></td>
									<td><input type="text" name="info[model][<?php echo $n;?>][view]" value="<?php echo $model['show_view'];?>" size="40" /></td>
								</tr>
								<?php $n++;}unset($n); ?>
							</table>
						</td>
					</tr>
					<tr>
						<th>工作流：</th>
						<td><?php echo $workflow;?></td>
					</tr>
					<tr>
						<th>是否在导航显示：</th>
						<td>
							<label><input type="radio" name="info[ismenu]" value="1" checked />是</label>
							<label><input type="radio" name="info[ismenu]" value="0" />否</label>
						</td>
					</tr>
					<tr>
						<th>描述：</th>
						<td><textarea name="info[description]" maxlength="255" style="width:100%;height:60px;"></textarea></td>
					</tr>
				</table>
			</fieldset>
			<div class="bk15"></div>
			<fieldset>
				<legend>生成HTML设置</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">栏目生成HTML：</th>
						<td>
							<label><input type="radio" name="setting[ishtml]" value="0" checked />否</label>
							<label><input type="radio" name="setting[ishtml]" value="1" />是</label>
						</td>
					</tr>
					<tr>
						<th>内容页生成HTML：</th>
						<td>
							<label><input type="radio" name="setting[content_ishtml]" value="0" checked />否</label>
							<label><input type="radio" name="setting[content_ishtml]" value="1" />是</label>
						</td>
					</tr>
				</table>
			</fieldset>
			<div class="bk15"></div>
			<fieldset>
				<legend>模板设置</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">栏目首页模板：</th>
						<td><input type="text" name="setting[category_view]" value="" size="50" /></td>
					</tr>
					<tr>
						<th>栏目列表页模板：</th>
						<td><input type="text" name="setting[list_view]" value="" size="50" /></td>
					</tr>
				</table>
			</fieldset>
			<div class="bk15"></div>
			<fieldset>
				<legend>SEO设置</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">栏目标题：</th>
						<td><input type="text" name="setting[meta_title]" value="" size="50" /></td>
					</tr>
					<tr>
						<th>栏目关键字：</th>
						<td><input type="text" name="setting[meta_keywords]" value="" size="50" /></td>
					</tr>
					<tr>
						<th>栏目描述：</th>
						<td><input type="text" name="setting[meta_description]" value="" size="50" /></td>
					</tr>
				</table>
			</fieldset>
			<div class="bk15"></div>
			<fieldset>
				<legend>权限设置</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">用户组权限：</th>
						<td></td>
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