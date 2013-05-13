<?php
class content_controller_category extends admin_class_controller
{
	public function action_init()
	{
		include $this->view('content','category','init');
	}

	public function action_add()
	{
		include $this->view('content','category','add');
	}

	public function action_edit()
	{
		include $this->view('content','category','edit');
	}

	public function action_delete()
	{
		
	}

	public function action_listorder()
	{
		
	}
}
?>