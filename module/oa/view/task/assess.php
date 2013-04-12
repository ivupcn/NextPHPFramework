<div class="pageContent">
	<div layoutH="2" style="float:left; display:block; overflow:auto; width:240px; border:solid 1px #CCC; line-height:21px; background:#fff">
	    <ul class="tree treeFolder">
			<li><a href="javascript">人员列表</a>
				<ul>
					{loop $user $r}
					<li><a href="<?php echo $this->_context->url('task::timeline@oa','userid/'.$r['userid']);?>" target="navTab" rel="oa_task_timeline">{$r['realname']}</a></li>
					{/loop}
				</ul>
			</li>
			
	     </ul>
	</div>
	<div id="dataBox" class="unitBox" style="margin-left:246px;">
		<table class="table" width="100%" layoutH="31" >
			<thead>
				<tr>
					<th align="center">id</th>
					<th align="center">名称</th>
					<th align="center">月份</th>
					<th align="center">自评</th>
					<th align="center">考评</th>
					<th align="center">月份</th>
					<th align="center">自评</th>
					<th align="center">考评</th>
				</tr>
			</thead>
			<tbody>
				{nloop $user $u}
				<tr>
					<td align="center">{$n}</td>
					<td align="center"><a href="?m=oa&c=task&a=assessuser&userid={$u['userid']}" target="navTab" rel="oa_task_assess_{$u['userid']}">{$u['realname']}</a></td>
					<td align="center">{date('Y-m',strtotime("-1 month", time()))}</td>
					<td align="center">
						{php echo $this->get_selfrated($u['userid'],true)}
					</td>
					<td align="center">
						{php echo $this->get_rated($u['userid'],true)}
					</td>
					<td align="center">{date('Y-m')}</td>
					<td align="center">
						{php echo $this->get_selfrated($u['userid'])}
					</td>
					<td align="center">
						{php echo $this->get_rated($u['userid'])}
					</td>
				</tr>
				{/nloop}
			</tbody>
		</table>
	</div>
</div>