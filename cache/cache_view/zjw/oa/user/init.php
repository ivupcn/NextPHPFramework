<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',$this->get_siteid()); ?>
<div id="main" class="main">
	<div id="sidebar">
		<fieldset>
			<legend>考评分数</legend>
		</fieldset>
		<div class="bk10"></div>
		<fieldset>
			<legend>最新动态</legend>
		</fieldset>
	</div>
	<div class="sleeve_main">
		<?php include $this->view('user','index','menu',$this->get_siteid()); ?>
		<div id="post">
			<fieldset>
				<legend>指派给我的任务</legend>
				<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"oa_model_task\" data=\"model=oa_model_task&tag_md5=aa03278e9a85007739e3f95876d812f3&action=select&where=assignedto+%3D+%24userid\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$data=oa_model_task::model()->select("assignedto = $userid",'*','','','','');?>
				<table class="table-list" width="100%">
					<tbody>
						<?php if(is_array($data)) foreach($data AS $r) { ?>
						<tr>
							<td align="center"><?php echo $r['id'];?></td>
							<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>"><?php echo $r['title'];?></a></td>
							<td align="center"><?php echo date('Y-m-d',$r['planendtime']);?></td>
							<td align="center"><?php echo $r['status'];?></td>
							<td align="center"></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
			</fieldset>
			<div class="bk10"></div>
			<fieldset>
				<legend>由我创建的任务</legend>
				<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"oa_model_task\" data=\"model=oa_model_task&tag_md5=48026f186c3b96528e431c2acd3a975a&action=select&where=sponsor+%3D+%24userid\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$data=oa_model_task::model()->select("sponsor = $userid",'*','','','','');?>
				<table class="table-list" width="100%">

					<tbody>
						<?php if(is_array($data)) foreach($data AS $r) { ?>
						<tr>
							<td align="center"><?php echo $r['id'];?></td>
							<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>"><?php echo $r['title'];?></a></td>
							<td align="center"><?php echo date('Y-m-d',$r['planendtime']);?></td>
							<td align="center"><?php echo $r['status'];?></td>
							<td align="center"></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
			</fieldset>
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>