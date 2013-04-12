<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div layoutH="2" style="float:left; display:block; overflow:auto; width:240px; border:solid 1px #CCC; line-height:21px; background:#fff">
	    <ul class="tree treeFolder">
			<li><a href="javascript">人员列表</a>
				<ul>
					<?php if(is_array($user)) foreach($user AS $r) { ?>
					<li><a href="<?php echo $this->_context->url('task::timeline@oa','userid/'.$r['userid']);?>" target="navTab" rel="oa_task_timeline"><?php echo $r['realname'];?></a></li>
					<?php } ?>
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
				<?php $n=1;if(is_array($user)) foreach($user AS $u) { ?>
				<tr>
					<td align="center"><?php echo $n;?></td>
					<td align="center"><a href="?m=oa&c=task&a=assessuser&userid=<?php echo $u['userid'];?>" target="navTab" rel="oa_task_assess_<?php echo $u['userid'];?>"><?php echo $u['realname'];?></a></td>
					<td align="center"><?php echo date('Y-m',strtotime("-1 month", time()));?></td>
					<td align="center">
						<?php echo $this->get_selfrated($u['userid'],true)?>
					</td>
					<td align="center">
						<?php echo $this->get_rated($u['userid'],true)?>
					</td>
					<td align="center"><?php echo date('Y-m');?></td>
					<td align="center">
						<?php echo $this->get_selfrated($u['userid'])?>
					</td>
					<td align="center">
						<?php echo $this->get_rated($u['userid'])?>
					</td>
				</tr>
				<?php $n++;}unset($n); ?>
			</tbody>
		</table>
	</div>
</div>