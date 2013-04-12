<?php
class admin_controller_view extends admin_class_controller
{
	public function action_init()
	{
		include $this->view('admin','view','init');
	}
}
?>