<?php
class test_controller_test
{
	public function action_init()
	{
		$data = test_model_test::model()->select();
		header('Content-type: application/json');
		$playlist = array(
			array('id'=>1,'title'=>'测试标题1','content'=>'测试内容1'),
			array('id'=>2,'title'=>'测试标题2','content'=>'测试内容2'),
			array('id'=>3,'title'=>'测试标题3','content'=>'测试内容3'),
			array('id'=>4,'title'=>'测试标题4','content'=>'测试内容4'),
			array('id'=>5,'title'=>'测试标题5','content'=>'测试内容5'),
			array('id'=>6,'title'=>'测试标题6','content'=>'测试内容6'),
			array('id'=>7,'title'=>'测试标题7','content'=>'测试内容7'),
			array('id'=>8,'title'=>'测试标题8','content'=>'测试内容8'),
			array('id'=>9,'title'=>'测试标题9','content'=>'测试内容9'),
			array('id'=>10,'title'=>'测试标题10','content'=>'测试内容10'),
			array('id'=>11,'title'=>'测试标题11','content'=>'测试内容11'),
			array('id'=>12,'title'=>'测试标题12','content'=>'测试内容12'),
			array('id'=>13,'title'=>'测试标题13','content'=>'测试内容13'),
			array('id'=>14,'title'=>'测试标题14','content'=>'测试内容14'),
			array('id'=>15,'title'=>'测试标题15','content'=>'测试内容15'),
			array('id'=>16,'title'=>'测试标题16','content'=>'测试内容16'),
			array('id'=>17,'title'=>'测试标题17','content'=>'测试内容17'),
			array('id'=>18,'title'=>'测试标题18','content'=>'测试内容18'),
			array('id'=>19,'title'=>'测试标题19','content'=>'测试内容19'),
			array('id'=>20,'title'=>'测试标题20','content'=>'测试内容20'),
			array('id'=>21,'title'=>'测试标题21','content'=>'测试内容21'),
			array('id'=>22,'title'=>'测试标题22','content'=>'测试内容22'),
			array('id'=>23,'title'=>'测试标题23','content'=>'测试内容23'),
			array('id'=>24,'title'=>'测试标题24','content'=>'测试内容24'),
			array('id'=>25,'title'=>'测试标题25','content'=>'测试内容25'),
			array('id'=>26,'title'=>'测试标题26','content'=>'测试内容26'),
			array('id'=>27,'title'=>'测试标题27','content'=>'测试内容27'),
			array('id'=>28,'title'=>'测试标题28','content'=>'测试内容28'),
			array('id'=>29,'title'=>'测试标题29','content'=>'测试内容29'),
		);
		echo json_encode($playlist);
	}
}
?>