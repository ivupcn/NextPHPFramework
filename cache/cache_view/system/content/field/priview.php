<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div class="pageFormContent" layoutH="23">
		<table width="100%" class="table_form contentWrap">
			<tbody>
			<?php if(is_array($forminfos)) foreach($forminfos AS $field => $info) { ?>	
			<tr>
		      <th width="80"><?php if($info['star']) { ?><font color="red">*</font><?php } ?> <?php echo $info['name'];?>
			  </th>
		      <td><?php echo $info['form'];?>  <?php echo $info['tips'];?></td>
		    </tr>
    		<?php } ?>
    		</tbody>
    	</table>
	</div>
</div>