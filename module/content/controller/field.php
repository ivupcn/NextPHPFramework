<?php
class content_controller_field extends admin_class_controller
{
	public function action_init()
	{
		$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
		$this->_cache_field($modelid);
		$base = content_model_field::model()->select(array('modelid'=>0,'siteid'=>SITEID),'*',100,'listorder ASC');
		$data = content_model_field::model()->select(array('modelid'=>$modelid),'*',100,'listorder ASC');
		require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
		include $this->view('content','field','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST())
		{

		}
		else
		{
			$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
			$f_datas = content_model_field::model()->select(array('modelid'=>$modelid),'field,name',100,'listorder ASC');
			foreach($f_datas as $_k=>$_v)
			{
				$exists_field[] = $_v['field'];
			}
			$all_field = array();
			foreach($fields as $_k=>$_v)
			{
				if(in_array($_k,$not_allow_fields) || in_array($_k,$exists_field) && in_array($_k,$unique_fields)) continue;
				$all_field[$_k] = $_v;
			}
			header("Cache-control: private");
			include $this->view('content','field','add');
		}
	}

	private function _cache_field($modelid = 0)
	{

	}
}
?>