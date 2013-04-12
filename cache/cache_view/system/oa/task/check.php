<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="?m=oa&c=task&a=check" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
    	<fieldset>
			<legend>任务进度</legend>
			<table width="100%" class="table_form contentWrap">
	    		<?php $n=1;if(is_array($progress)) foreach($progress AS $r) { ?>
	    		<tr class="progressbox">
	    			<td width="30">
	    				<strong><?php echo $n;?>#</strong><br /><br />
	    			</td>
	    			<td>
	    				标题：<?php echo $r['title'];?><br />
	    				 URL：<a href="<?php echo $r['url'];?>" target="_blank"><?php echo $r['url'];?></a><br />
	    				备注：<?php echo $r['description'];?>
	    			</td>
	    		</tr>
	    		<?php $n++;}unset($n); ?>
	    	</table>
		</fieldset>
		<div class="bk10"></div>
		<fieldset>
			<legend>汇报任务</legend>
			<table width="100%" class="table_form contentWrap">
				<tr>
					<th width="80">任务名称：</th>
					<td><?php echo $info['title'];?></td>
				</tr>
				<tr>
					<th width="80">指派给：</th>
					<td><?php echo $userop->get_userinfo($info['assignedto'],'realname');?></td>
				</tr>
				<tr>
					<th>任务类型：</th>
					<td><?php echo $category[$info['tagid']]['tagname'];?></td>
				</tr>
				<tr>
					<th>完成时间：</th>
					<td><?php echo date('Y-m-d H:i:s',SYS_TIME);?></td>
				</tr>
				<tr>
					<th>附件：</th>
					<td>
						<?php if($info['reportatt']) { ?>
						<?php $reportatt = string2array($info['reportatt'])?>
						<?php if(is_array($reportatt)) foreach($reportatt AS $r) { ?>
						<li><a href="<?php echo $siteinfo['url'];?><?php echo substr($r,1);?>" target="_blank"><?php echo $siteinfo['url'];?><?php echo substr($r,1);?></a></li>
						<?php } ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th>备注：</th>
					<td><?php echo $info['report'];?></td>
				</tr>
				<tr>
					<th>任务分值：</th>
					<td>
						<?php $setting = string2array($category[$info['tagid']]['setting'])?>
          				<?php echo $setting['presentpoint'];?>
					</td>
				</tr>
				<tr>
					<th>自评分值：</th>
					<td><?php echo $info['selfrated'];?></td>
				</tr>
				<tr>
					<th>考评分值：</th>
					<td>
          				<?php $setting = string2array($category[$info['tagid']]['setting'])?>
          				<input type="text" name="compentedrated" class="required input-text" value="<?php echo $setting['presentpoint'];?>" size="3" />
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
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="hidden" name="tagid" value="<?php echo $info['tagid'];?>">
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>