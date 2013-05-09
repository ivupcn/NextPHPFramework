<?php
class test_controller_test
{
	public function action_init()
	{
		// $update = test_model_test::model()->SET(array('title'=>'ivup'))->WHERE(array('id'=>1330))->update();
		// var_dump($update);
		// $delete = test_model_test::model()->WHERE(array('id'=>1330))->delete();
		// var_dump($delete);
		// $page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result = admin_model_menu::model()->WHERE(array('sys'=>0))->ORDER('listorder ASC,id ASC')->select();
		var_dump($result);
		// var_dump($data);
		// echo test_model_test::model()->pages;
		// $sum = test_model_test::model()->SUM('id');
		// var_dump($sum);
	}
}
?>