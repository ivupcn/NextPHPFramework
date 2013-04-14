<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',SITEID); ?>
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right:0">
		<?php include $this->view('user','index','menu',SITEID); ?>
		<div id="post">
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',SITEID); ?>