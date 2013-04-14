{view 'user','index','header',SITEID}
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right: 0">
		{view 'user','index','menu',SITEID}
		<div id="post">
			{model:user_model_user action="select" where="siteid = SITEID"}
			<table class="table-list" width="100%">
				<thead>
					<tr>
						<th align="center">姓名</th>
						<th align="left">月份</th>
						<th align="center">网站建设</th>
						<th align="center">广告设计</th>
						<th align="center">新闻采访</th>
						<th align="center">ITV维护</th>
						<th align="center">论坛维护</th>
						<th>三维全景</th>
						<th>软件开发</th>
						<th>总分</th>
					</tr>
				</thead>
				<tbody>
					{loop $data $r}
					<tr>
						<td align="center">{php echo $this->get_userinfo($r['userid'],'realname')}</td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
					</tr>
					{/loop}
				</tbody>
			</table>
			{/model}
		</div>
	</div>
</div>
{view 'user','index','footer',SITEID}