<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="treeMenu" fillSpace="sidebar">
	<ul class="tree treeFolder expand">
	<?php
	defined('IN_ADMIN') or exit('No permission resources.');
	foreach($datas as $_value) {
		echo '<li><a>'.$_value['name'].'</a><ul>';
		$sub_array = $this->admin_menu($_value['id']);
		foreach($sub_array as $_key=>$_m) {
			//附加参数
			$data = $_m['data'] ? '&'.$_m['data'] : '';
			echo '<li><a href="'.$this->_context->url($_m['c'].'::'.$_m['a'].'@'.$_m['m']).$data.'" target="navTab" rel="'.$_m['m'].'_'.$_m['c'].'_'.$_m['a'].'">'.$_m['name'].'</a></li>';
		}
		echo '</ul></li>';
	}
	?>
	</ul>
</div>
