{view 'user','index','header',SITEID}
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
		{view 'user','index','menu',SITEID}
		<div id="post">
			<fieldset>
				<legend>指派给我的任务</legend>
				{model:oa_model_task action="select" where="assignedto = $userid"}
				<table class="table-list" width="100%">
					<tbody>
						{loop $data $r}
						<tr>
							<td align="center">{$r['id']}</td>
							<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>">{$r['title']}</a></td>
							<td align="center">{date('Y-m-d',$r['planendtime'])}</td>
							<td align="center">{$r['status']}</td>
							<td align="center"></td>
						</tr>
						{/loop}
					</tbody>
				</table>
				{/model}
			</fieldset>
			<div class="bk10"></div>
			<fieldset>
				<legend>由我创建的任务</legend>
				{model:oa_model_task action="select" where="sponsor = $userid"}
				<table class="table-list" width="100%">

					<tbody>
						{loop $data $r}
						<tr>
							<td align="center">{$r['id']}</td>
							<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>">{$r['title']}</a></td>
							<td align="center">{date('Y-m-d',$r['planendtime'])}</td>
							<td align="center">{$r['status']}</td>
							<td align="center"></td>
						</tr>
						{/loop}
					</tbody>
				</table>
				{/model}
			</fieldset>
		</div>
	</div>
</div>
{view 'user','index','footer',SITEID}