<div class="pageContent">
  <form action="?m=oa&c=task&a=check" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<fieldset>
			<legend>任务进度</legend>
			<table width="100%" class="table_form contentWrap">
	    		{nloop $progress $r}
	    		<tr class="progressbox">
	    			<td width="30">
	    				<strong>{$n}#</strong><br /><br />
	    			</td>
	    			<td>
	    				标题：{$r['title']}<br />
	    				 URL：<a href="{$r['url']}" target="_blank">{$r['url']}</a><br />
	    				备注：{$r['description']}
	    			</td>
	    		</tr>
	    		{/nloop}
	    	</table>
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
					<th width="80">指派给：</th>
					<td><?php echo $userop->get_userinfo($info['assignedto'],'realname');?></td>
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
					<th>附件：</th>
					<td>
						{if $info['reportatt']}
						{php $reportatt = string2array($info['reportatt'])}
						{loop $reportatt $r}
						<li><a href="{$siteinfo['url']}{substr($r,1)}" target="_blank">{$siteinfo['url']}{substr($r,1)}</a></li>
						{/loop}
						{/if}
					</td>
				</tr>
				<tr>
					<th>备注：</th>
					<td>{$info['report']}</td>
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
					<td>{$info['selfrated']}</td>
				</tr>
				<tr>
					<th>考评分值：</th>
					<td>
          				{php $setting = string2array($category[$info['tagid']]['setting'])}
          				<input type="text" name="compentedrated" class="required input-text" value="{$setting['presentpoint']}" size="3" />
					</td>
				</tr>
				<tr>
					<th>验收结论：</th>
					<td>
						<label><input type="radio" name="result" value="1" checked>合格</label>
						<label><input type="radio" name="result" value="0">退回</label>
					</td>
				</tr>
			</table>
		</fieldset>
    </div>
    <input type="hidden" name="id" value="{$id}">
    <input type="hidden" name="tagid" value="{$info['tagid']}">
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>