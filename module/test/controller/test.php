<?php
class test_controller_test
{
	public function action_init()
	{
		$data = test_model_test::model()->select();
		var_dump($data);
	}
}
?>