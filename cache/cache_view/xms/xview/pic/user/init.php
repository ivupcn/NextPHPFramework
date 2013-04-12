<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',$this->get_siteid()); ?>
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right:0">
		<?php include $this->view('user','index','menu',$this->get_siteid()); ?>
		<div id="post">
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>