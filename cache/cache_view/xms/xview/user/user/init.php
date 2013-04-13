<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
<div class="panelBar">
	<ul class="toolBar">
		<li><a class="add" href="<?php echo $this->_context->url('user::add@user'); ?>" target="dialog" mask="true" maxable="false" rel="user_user_add" width="500" height="300"><span>添加会员</span></a></li>
	</ul>
</div>
<form id="pagerForm" action="<?php echo $this->_context->url('user::init@user'); ?>" method="post">
	<input type="hidden" name="pageNum" value="<?php echo $page;?>" />
    <table class="table" width="100%" layoutH="82" rel="jbsxBox">
        <thead>
		<tr>
		<th align="center">ID</th>
		<th>EMail</th>
		<th>真实姓名</th>
		<th>所属角色</th>
		<th>所属用户组</th>
		<th >最后登录IP</th>
		<th>最后登录时间</th>
		<th align="center">操作管理</th>
		</tr>
        </thead>
        <tbody>
<?php $admin_founders = explode(',',Next::config('system','admin_founders','1'))?>
<?php if(is_array($infos)) foreach($infos AS $info) { ?>
<?php if(!in_array($info['userid'],$admin_founders)) { ?>
<tr>
<td align="center"><?php echo $info['userid'];?></td>
<td><?php echo $info['email'];?></td>
<td><?php echo $info['realname'];?></td>
<td>
	<?php if($info['roleid']) { ?>
	<?php $role_arr = normalize($info['roleid'])?>
	<?php $r_arr = array()?>
	<?php if(is_array($role_arr)) foreach($role_arr AS $r) { ?>
	<?php $r_arr[] = $roles[$r]?>
	<?php } ?>
	<?php echo implode('，',$r_arr);?>
	<?php } ?>
</td>
<td>
	<?php if(!empty($info['groupid'])) { ?>
	<?php echo $groups[$info['groupid']]['name'];?>
	<?php } ?>
</td>
<td><?php echo $info['lastloginip'];?></td>
<td><?php echo $info['lastlogintime'] ? date('Y-m-d H:i:s',$info['lastlogintime']) : ''?></td>
<td align="center">
<a href="<?php echo $this->_context->url('user::edit@user','userid/'.$info['userid']); ?>" target="dialog" mask="true" maxable="false" rel="user_user_edit" width="500" height="300">修改</a> | 
<a href="<?php echo $this->_context->url('user::delete@user','user/'.$info['userid']); ?>" target="ajaxTodo" title="确定要删除吗?">删除</a>
</td>
</tr>
<?php } ?>
<?php } ?>
</tbody>
</table>
<div class="panelBar">
	<?php echo $pages;?>
</div>
</form>
</div>
