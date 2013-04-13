<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',ROUTE_S); ?>
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right:0">
		<?php include $this->view('user','index','menu',ROUTE_S); ?>
		<div id="post">
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',ROUTE_S); ?>