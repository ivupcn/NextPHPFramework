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

	public function action_add()
	{
		if($this->_context->isPOST() &&  content_model_model::model()->validate($_POST['info']))
		{
			$info = $_POST['info'];
			$info['siteid'] = ROUTE_S;
			$modelid = content_model_model::model()->insert($info,true);
			$model_sql = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'model.sql');
			$tablepre = content_model_model::model()->db_tablepre;
			$tablename = $info['tablename'];
			$model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
			$model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
			$model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
			$model_sql = str_replace('$modelid',$modelid,$model_sql);
			$model_sql = str_replace('$siteid',ROUTE_S,$model_sql);

			content_model_model::model()->sql_execute($model_sql);
			$this->_cache_field($modelid);

			$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'closeCurrent','content_model_init');
		}
		else
		{
			include $this->view('content','model','add');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST() &&  content_model_model::model()->validate($_POST['info']))
		{

		}
		else
		{
			include $this->view('content','model','edit');
		}
	}

	public function action_delete()
	{
		$modelid = intval($_GET['modelid']);
		$model_cache = getcache('model','commons');
		$model_table = $model_cache[$modelid]['tablename'];
		content_model_field::model()->delete(array('modelid'=>$modelid,'siteid'=>ROUTE_S));
		content_model_model::model()->drop_table($model_table);
		content_model_model::model()->drop_table($model_table.'_data');
		content_model_model::model()->delete(array('modelid'=>$modelid,'siteid'=>ROUTE_S));

		$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'','content_model_init');
	}


	/**
	 * 更新指定模型字段缓存
	 * 
	 * @param $modelid 模型id
	 */
	private function _cache_field($modelid = 0)
	{
		$field_array = array();
		$fields = content_model_field::model()->select(array('modelid'=>$modelid,'disabled'=>$disabled),'*',100,'listorder ASC');
		foreach($fields as $_value)
		{
			$setting = string2array($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		setcache('model_field_'.$modelid,$field_array,'model');
		return true;
	}
}
?>