<?php
class content_controller_model extends admin_class_controller
{
	public function action_init()
	{
		$page = isset($_POST['pageNum']) ? intval($_POST['pageNum']) : '1';
		$data = content_model_model::model()->listinfo(array('siteid'=>SITEID,'type'=>0),'',$page,30);
		$pages = content_model_model::model()->pages;
		$this->_cache();
		include $this->view('content','model','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() &&  content_model_model::model()->validate($_POST['info']))
		{
			$info = $_POST['info'];
			$info['siteid'] = SITEID;
			$modelid = content_model_model::model()->insert($info,true);
			$model_sql = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'model.sql');
			$tablepre = content_model_model::model()->db_tablepre;
			$tablename = $info['tablename'];
			$model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
			$model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
			$model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
			$model_sql = str_replace('$modelid',$modelid,$model_sql);
			$model_sql = str_replace('$siteid',SITEID,$model_sql);

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
			$modelid = isset($_POST['modelid']) && intval($_POST['modelid']) ? intval($_POST['modelid']) : $this->_app->showmessage('300','操作失败！');
			$info = $_POST['info'];
			content_model_model::model()->update($info,array('modelid'=>$modelid));
			$this->_cache_field($modelid);
			$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'closeCurrent','content_model_init');
		}
		else
		{
			$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
			$r = content_model_model::model()->get_one(array('modelid'=>$modelid));
			extract($r);
			include $this->view('content','model','edit');
		}
	}

	public function action_delete()
	{
		$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
		$model_cache = getcache('model','model');
		$model_table = $model_cache[$modelid]['tablename'];
		content_model_field::model()->delete(array('modelid'=>$modelid,'siteid'=>SITEID));
		content_model_model::model()->drop_table($model_table);
		content_model_model::model()->drop_table($model_table.'_data');
		content_model_model::model()->delete(array('modelid'=>$modelid,'siteid'=>SITEID));

		$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'','content_model_init');
	}

	private function _cache()
	{
		require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
		//更新内容模型类：表单生成、入库、更新、输出
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype)
		{
			$cache_data = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$classtype.'.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue) {
				if(file_exists(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field.DIRECTORY_SEPARATOR.$classtype.'.php')) {
					$cache_data .= file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field.DIRECTORY_SEPARATOR.$classtype.'.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_model'.DIRECTORY_SEPARATOR.'cache_data'.DIRECTORY_SEPARATOR.$classtype.'.php',$cache_data);
			@chmod(Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_model'.DIRECTORY_SEPARATOR.'cache_data'.DIRECTORY_SEPARATOR.$classtype.'.php',0777);
		}
		//更新模型数据缓存
		$model_array = array();
		$datas = content_model_model::model()->select(array('type'=>0));
		foreach ($datas as $r) {
			if(!$r['disabled']) $model_array[$r['modelid']] = $r;
		}
		setcache('model', $model_array, 'model');
		return true;
	}

	private function _cache_field($modelid = 0)
	{
		$field_array = array();
		$fields = content_model_field::model()->select(array('modelid'=>$modelid,'disabled'=>0),'*',100,'listorder ASC');
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