<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',$this->get_siteid()); ?>
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right: 0">
		<?php include $this->view('user','index','menu',$this->get_siteid()); ?>
		<div id="post">
			<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"oa_model_task\" data=\"model=oa_model_task&tag_md5=25657cb5eb843e9335c490e54a5af239&action=listinfo&order=id+DESC&page=%24page\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$page = max(intval($page), 1);$offset = 20*($page-1);$data=oa_model_task::model()->select('','*',$offset.',20','id DESC','','');$count = oa_model_task::model()->get_one('', "COUNT(*) AS num");$pages = pages($count["num"], $page, 20);?>
			<table class="table-list" width="100%">
				<thead>
					<tr>
						<th align="center">ID</th>
						<th align="left">任务名称</th>
						<th align="center">截至时间</th>
						<th align="center">指派给</th>
						<th align="center">状态</th>
						<th align="center">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($data)) foreach($data AS $r) { ?>
					<tr>
						<td align="center"><?php echo $r['id'];?></td>
						<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>"><?php echo $r['title'];?></a></td>
						<td align="center"><?php echo date('Y-m-d',$r['planendtime']);?></td>
						<td align="center"><?php echo $this->get_userinfo($r['assignedto'],'realname')?></td>
						<td align="center"><?php echo $r['status'];?></td>
						<td align="center"></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="pages"><?php echo $pages;?></div>
			<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>