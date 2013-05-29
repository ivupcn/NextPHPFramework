<div class="pageContent">
	<form action="{url 'category::add@content'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="52">
			<fieldset>
				<legend>基本选项</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">上级栏目：</th>
						<td>
							<input type="hidden" name="info[type]" value="{$info['type']}" />
							<?php echo form::select_category('category_content_'.SITEID,$info['parentid'],'name="info[parentid]"','≡ 作为一级栏目 ≡',0,-1);?>
						</td>
					</tr>
					<tr>
						<th>栏目名称：</th>
						<td><input type="text" name="info[catname]" class="input-text required" value="{$info['catname']}" /></td>
					</tr>
					<tr>
						<th>英文目录：</th>
						<td><input type="text" name="info[catdir]" class="input-text required" value="{$info['catdir']}" /><input type="hidden" name="oldcatdir" value="{$info['catdir']}" /></td>
					</tr>
					<tr>
						<th>栏目图片：</th>
						<td><?php echo form::images('info[image]', 'image', '', 'content');?></td>
					</tr>
					<tr>
						<th>选择模型：</th>
						<td>
							<table>
								{nloop $models $model}
								<tr>
									<td><input type="checkbox" name="model[{$n}][enable]" value="1"{if isset($setting['model'][$n]['enable']) && !empty($setting['model'][$n]['enable'])} checked{/if} /></td>
									<td><input type="hidden" name="model[{$n}][modelid]" value="{$model['modelid']}" /><input type="hidden" name="model[{$n}][name]" value="{$model['name']}" />{$model['name']}</td>
									<td><input type="text" name="model[{$n}][view]" value="{if isset($setting['model'][$n]['view'])}{$setting['model'][$n]['view']}{/if}" size="40" /></td>
								</tr>
								{/nloop}
							</table>
						</td>
					</tr>
					<tr>
						<th>工作流：</th>
						<td>{$workflow}</td>
					</tr>
					<tr>
						<th>是否在导航显示：</th>
						<td>
							<label><input type="radio" name="info[ismenu]" value="1"{if $info['ismenu'] == 1} checked{/if} />是</label>
							<label><input type="radio" name="info[ismenu]" value="0"{if $info['ismenu'] == 0} checked{/if} />否</label>
						</td>
					</tr>
					<tr>
						<th>描述：</th>
						<td><textarea name="info[description]" maxlength="255" style="width:100%;height:60px;">{$info['description']}</textarea></td>
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
							<label><input type="radio" name="setting[ishtml]" value="0"{if $setting['ishtml'] == 0} checked{/if} />否</label>
							<label><input type="radio" name="setting[ishtml]" value="1"{if $setting['ishtml'] == 1} checked{/if} />是</label>
						</td>
					</tr>
					<tr>
						<th>内容页生成HTML：</th>
						<td>
							<label><input type="radio" name="setting[content_ishtml]" value="0"{if $setting['content_ishtml'] == 0} checked{/if} />否</label>
							<label><input type="radio" name="setting[content_ishtml]" value="1"{if $setting['content_ishtml'] == 1} checked{/if} />是</label>
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
						<td><input type="text" name="setting[category_view]" value="{$setting['category_view']}" size="50" /></td>
					</tr>
					<tr>
						<th>栏目列表页模板：</th>
						<td><input type="text" name="setting[list_view]" value="{$setting['list_view']}" size="50" /></td>
					</tr>
				</table>
			</fieldset>
			<div class="bk15"></div>
			<fieldset>
				<legend>SEO设置</legend>
				<table width="100%" class="table_form contentWrap">
					<tr>
						<th width="110">栏目标题：</th>
						<td><input type="text" name="setting[meta_title]" value="{$setting['meta_title']}" size="50" /></td>
					</tr>
					<tr>
						<th>栏目关键字：</th>
						<td><input type="text" name="setting[meta_keywords]" value="{$setting['meta_keywords']}" size="50" /></td>
					</tr>
					<tr>
						<th>栏目描述：</th>
						<td><input type="text" name="setting[meta_description]" value="{$setting['meta_description']}" size="50" /></td>
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
		<input type="hidden" name="catid" value="{$catid}">
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
</div>