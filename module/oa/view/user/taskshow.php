{view 'user','index','header',ROUTE_S}
<div id="main" class="main">

	<div class="sleeve_main" style="margin-right: 0">
		{view 'user','index','menu',ROUTE_S}
		<div id="post">
			<h1>TASK #{$taskinfo['id']}　{$taskinfo['title']}</h1>
			<div id="content-sidebar">
				<fieldset>
					<legend>基本信息</legend>
					<table class="table-info border-none" width="100%">
						<tbody>
							<tr>
								<th style="text-align:left">任务类型：</th>
								<td>{$taskinfo['tagid']}</td>
							</tr>
							<tr>
								<th style="text-align:left">任务分值：</th>
								<td></td>
							</tr>
							<tr>
								<th style="text-align:left">创 建 者 ：</th>
								<td>{php echo $this->get_userinfo($taskinfo['sponsor'],'realname')}</td>
							</tr>
							<tr>
								<th style="text-align:left">指 派 给 ：</th>
								<td>{php echo $this->get_userinfo($taskinfo['assignedto'],'realname')}</td>
							</tr>
							<tr>
								<th style="text-align:left">任务状态：</th>
								<td>{$taskinfo['status']}</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>工时信息</legend>
					<table class="table-info border-none" width="100%">
						<tbody>
							<tr>
								<th style="text-align:left">预计开始时间：</th>
								<td>{date('Y-m-d',$taskinfo['planstarttime'])}</td>
							</tr>
							<tr>
								<th style="text-align:left">实际开始时间：</th>
								<td>{if isset($taskinfo['actualstarttime'])}{date('Y-m-d',$taskinfo['actualstarttime'])}{/if}</td>
							</tr>
							<tr>
								<th style="text-align:left">预计结束时间：</th>
								<td>{date('Y-m-d',$taskinfo['planendtime'])}</td>
							</tr>
							<tr>
								<th style="text-align:left">实际结束时间：</th>
								<td>{if isset($taskinfo['actualendtime'])}{date('Y-m-d',$taskinfo['actualendtime'])}{/if}</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="content">
				<fieldset>
					<legend>任务描述</legend>
					<div class="text">
						{$taskinfo['intro']}
					</div>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>附　　件</legend>
					<div class="attachment">
						{$taskinfo['attachment']}
					</div>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>考评细则</legend>
					<div class="text">
						{$taskinfo['tagid']}
					</div>
				</fieldset>
			</div>
				
			
			
		</div>
	</div>
</div>
{view 'user','index','footer',ROUTE_S}