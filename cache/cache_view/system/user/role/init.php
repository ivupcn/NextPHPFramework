<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
		<li><a class="add" href="?m=user&c=role&a=add" target="dialog" mask="true" maxable="false" rel="user_role_add" width="500" height="300"><span>添加角色</span></a></li>
		</ul>
	</div>
    <table class="table" width="100%" layoutH="375">
        <thead>
			<tr>
			<th align="center">ID</th>
			<th>角色名</th>
			<th>角色描述</th>
			<th align="center">状态</th>
			<th align="center">管理操作</th>
			</tr>
        </thead>
		<tbody>
			<?php if(is_array($infos)) foreach($infos AS $info) { ?>
			<?php if($info['roleid'] > 1) { ?>
			<tr>
			<td align="center"><?php echo $info['roleid'];?></td>
			<td><?php echo $info['rolename'];?></td>
			<td><?php echo $info['description'];?></td>
			<td align="center">
				<a href="?m=user&c=role&a=changeStatus&roleid=<?php echo $info['roleid'];?>&disabled=<?php if($info['disabled']==1) { ?>0<?php } else { ?>1<?php } ?>" target="ajaxTodo">
				<?php if($info['disabled']) { ?><font color="blue">×</font><?php } else { ?><font color="red">√</font><?php } ?>
				</a>
			</td>
			<td align="center">
			<a href="?m=user&c=user&a=roleUser&roleid=<?php echo $info['roleid'];?>" target="ajax" rel="userBox">成员管理</a> | 
			<a href="?m=user&c=role&a=edit&roleid=<?php echo $info['roleid'];?>" target="dialog" mask="true" maxable="false" rel="user_role_edit" width="500" height="300">修改</a> | 
			<a href="?m=user&c=role&a=delete&roleid=<?php echo $info['roleid'];?>" target="ajaxTodo" title="确定要删除吗?">删除</a>
			</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
	</table>
	<div id="userBox" class="unitBox">

	</div>
</div>