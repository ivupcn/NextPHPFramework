<div class="pageContent">
    <table class="table" width="100%" layoutH="315">
        <thead>
		<tr>
		<th align="center">ID</th>
		<th>EMail</th>
		<th>真实姓名</th>
		<th>所属角色</th>
		<th>所属用户组</th>
		<th>最后登录IP</th>
		<th>最后登录时间</th>
		<th align="center">操作管理</th>
		</tr>
        </thead>
        <tbody>
{php $admin_founders = explode(',',Next::config('system','admin_founders','1'))}
{loop $infos $info}
{if !in_array($info['userid'],$admin_founders)}
<tr>
<td align="center">{$info['userid']}</td>
<td>{$info['email']}</td>
<td>{$info['realname']}</td>
<td>
	{if $info['roleid']}
	{php $role_arr = normalize($info['roleid'])}
	{php $r_arr = array()}
	{loop $role_arr $r}
	{php $r_arr[] = $roles[$r]}
	{/loop}
	{implode('，',$r_arr)}
	{/if}
</td>
<td>
	{if !empty($info['groupid'])}
	{$groups[$info['groupid']]['name']}
	{/if}
</td>
<td>{$info['lastloginip']}</td>
<td>{php echo $info['lastlogintime'] ? date('Y-m-d H:i:s',$info['lastlogintime']) : ''}</td>
<td align="center">
<a  href="{url 'user::edit@user','userid/'.$info['userid']}" target="dialog" mask="true" maxable="false" rel="user_user_edit" width="500" height="300">修改</a> | 
<a href="{url 'user::delete@user','userid/'.$info['userid']}" target="ajaxTodo" title="确定要删除吗?">删除</a>
</td>
</tr>
{/if}
{/loop}
</tbody>
</table>
</div>
