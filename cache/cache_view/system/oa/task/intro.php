<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
<div class="pageFormContent" layoutH="52">
	<?php if($info['attachment']) { ?>
	<?php $attachment = string2array($info['attachment'])?>
	<fieldset>
		<legend>附件</legend>
		<?php if(is_array($attachment)) foreach($attachment AS $r) { ?>
		<li><a href="<?php echo $siteinfo['url'];?><?php echo substr($r,1);?>" target="_blank"><?php echo $siteinfo['url'];?><?php echo substr($r,1);?></a></li>
		<?php } ?>
	</fieldset>
	<?php } ?>
	<?php if($info['intro']) { ?>
	<fieldset>
		<legend>备注</legend>
		<?php echo $info['intro'];?>
	</fieldset>
	<?php } ?>
</div>
<div class="formBar">
  <ul>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</div>