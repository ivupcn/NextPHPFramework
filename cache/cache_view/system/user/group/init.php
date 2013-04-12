<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="?m=user&c=group&a=add" target="dialog" mask="true" maxable="false" rel="user_group_add" width="800" height="400"><span>添加用户组</span></a></li>
    </ul>
 	</div>
<form action="?m=user&c=group&a=sort" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
<table class="table" width="100%" layoutH="92">
	<thead>
		<tr>
			<th width="30px"><input type="checkbox" group="groupid[]" class="checkboxCtrl"></th>
			<th >ID</th>
			<th>排序</th>
			<th>用户组名</th>
			<th align="center">系统组</th>
			<th align="center">会员数</th>
			<th align="center">星星数</th>
			<th align="center">积分小于</th>
			<th align="center">允许访问</th>
			<th align="center">允许上传附件</th>
			<th align="center">投稿权限</th>
			<th align="center">投稿不需审核</th>
			<th align="center">搜索权限</th>
			<th align="center">自助升级</th>
			<th align="center">发短消息</th>
			<th align="center">邮件认证</th>
			<th align="center">管理操作</th>
		</tr>
	</thead>
<tbody>
<?php
	foreach($user_group_list as $k=>$v) {
?>
    <tr>
		<td><?php if(!$v['issystem']) {?><input type="checkbox" value="<?php echo $v['groupid']?>" name="groupid[]"><?php }?></td>
		<td><?php echo $v['groupid']?></td>
		<td><input type="text" name="sort[<?php echo $v['groupid']?>]" size="1" value="<?php echo $v['sort']?>"></td>
		<td title="<?php echo $v['description']?>"><?php echo $v['name']?></td>
		<td align="center"><?php echo $v['issystem'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['usernum']?></td>
		<td align="center"><?php echo $v['starnum']?></td>
		<td align="center"><?php echo $v['point']?></td>
		<td align="center"><?php echo $v['allowvisit'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowattachment'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowpost'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowpostverify'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowsearch'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowupgrade'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['allowsendmessage'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><?php echo $v['spamcertification'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
		<td align="center"><a href="?m=user&c=group&a=edit&groupid=<?php echo $v['groupid'];?>" target="dialog" mask="true" maxable="false" rel="user_group_edit" width="800" height="400">修改</a> | <a href="?m=user&c=group&a=delete&groupid=<?php echo $v['groupid'];?>" target="ajaxTodo" title="确定要删除吗?">删除</a></td>
    </tr>
<?php
	}
?>
</tbody>
 </table>
   <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">排序</button></div></div></li>
      </ul>
    </div>
</form>
</div>