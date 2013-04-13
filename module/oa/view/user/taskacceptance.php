{view 'user','index','header',ROUTE_S}
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right: 0">
		{view 'user','index','menu',ROUTE_S}
		<div id="post">
			{model:oa_model_task action="listinfo" order="id DESC" page="$page"}
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
					{loop $data $r}
					<tr>
						<td align="center">{$r['id']}</td>
						<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>">{$r['title']}</a></td>
						<td align="center">{date('Y-m-d',$r['planendtime'])}</td>
						<td align="center">{php echo $this->get_userinfo($r['assignedto'],'realname')}</td>
						<td align="center">{$r['status']}</td>
						<td align="center"></td>
					</tr>
					{/loop}
				</tbody>
			</table>
			<div id="pages">{$pages}</div>
			{/model}
		</div>
	</div>
</div>
{view 'user','index','footer',ROUTE_S}