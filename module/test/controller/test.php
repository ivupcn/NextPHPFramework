<?php
class test_controller_test
{
	public function action_init()
	{
		$data = test_model_test::model()->select();
		header('Content-type: application/json');
		echo json_encode($data);
	}
}
?>