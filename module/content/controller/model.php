<?php
class content_controller_model extends admin_class_controller
{
	public function action_init()
	{
		$page = isset($_POST['pageNum']) ? intval($_POST['pageNum']) : '1';
		$data = content_model_model::model()->listinfo(array('siteid'=>ROUTE_S,'type'=>0),'',$page,30);
		$pages = content_model_model::model()->pages;
		include $this->view('content','model','init');
	}
}
?>