<?php
class test_controller_test
{
	public function action_init()
	{
		// $update = test_model_test::model()->SET(array('title'=>'ivup'))->WHERE(array('id'=>1330))->update();
		// var_dump($update);
		// $delete = test_model_test::model()->WHERE(array('id'=>1330))->delete();
		// var_dump($delete);
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$data = test_model_test::model()->TABLE(array('x_test'=>'s'))->PAGE(array('page'=>$page))->select();
		// var_dump($data);
		echo test_model_test::model()->pages;
		// $sum = test_model_test::model()->SUM('id');
		// var_dump($sum);
	}
}
?>