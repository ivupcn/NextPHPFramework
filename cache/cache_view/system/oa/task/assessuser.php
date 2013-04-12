<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<table class="table" width="100%" layoutH="31">
	    <thead>
	      <tr>
	        <th>任务类型</th>
	        <th><?php echo date('Y-m',strtotime("-1 month", time()));?></th>
	        <th>自评</th>
	        <th>考评</th>
	        <th><?php echo date('Y-m');?></th>
	        <th>自评</th>
	        <th>考评</th>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php echo $assess;?>
	      </tr>
	    </tbody>
	</table>
</div>