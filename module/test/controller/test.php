<?php
class test_controller_test
{
	public function action_init()
	{
		$field_array = array();
		$basefield = content_model_field::model()->WHERE(array('modelid'=>0,'siteid'=>SITEID))->ORDER('listorder ASC')->select();
		$modelfield = content_model_field::model()->WHERE(array('modelid'=>66,'disabled'=>0))->ORDER('listorder ASC')->select();
		$fields = array_merge($basefield, $modelfield);
		$fields = arr::sortbycol($fields,'listorder');
		foreach($fields as $_value)
		{
			$setting = unserialize($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		var_dump($field_array);

	}
}
?>