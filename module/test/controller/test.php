<?php
class test_controller_test
{
	public function action_init()
	{
		$infos = test_model_test::model()->PAGE(array('page'=>2,'pagesize'=>20))->ORDER('id ASC')->select();
		foreach ($infos as $key) {
			var_dump($key);
		}
	}
}
?>