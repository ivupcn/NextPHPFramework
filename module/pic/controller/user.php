<?php
class pic_controller_user extends user_class_controller
{
	public function action_init()
	{
		include $this->view('pic','user','init',SITEID);
	}
}
?>