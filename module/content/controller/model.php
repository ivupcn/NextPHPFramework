<?php
class content_controller_model extends admin_class_controller
{
	public function action_init()
	{
		$page = isset($_POST['pageNum']) ? intval($_POST['pageNum']) : '1';
		$data = content_model_model::model()->WHERE(array('siteid'=>SITEID,'type'=>0))->PAGE(array('page'=>$page))->select();
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
			$config = Next::config('database','default');
			$modelid = content_model_model::model()->FIELDVALUE($info)->insert();
			$tablepre = $config['tablepre'];
			$model_sql = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'model.sql');
			$tablename = $info['tablename'];
			$basetablename = 'post_'.SITEID;
			if(!content_model_model::table_exists($basetablename))
			{
				$base_sql = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'base.sql');
				$base_sql = str_replace('$basic_table', $tablepre.$basetablename, $base_sql);
				$base_sql = str_replace('$base_model_field',$tablepre.'model_field', $base_sql);
				$base_sql = str_replace('$siteid',SITEID,$base_sql);	
				content_model_model::sql_execute($base_sql);
			}
			$model_sql = str_replace('$table_data',$tablepre.$basetablename.'_'.$tablename, $model_sql);
			$model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
			$model_sql = str_replace('$modelid',$modelid,$model_sql);
			$model_sql = str_replace('$siteid',SITEID,$model_sql);	
			content_model_model::sql_execute($model_sql);
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
		if($this->_context->isPOST() &&  content_model_model::model()->validate($_POST['info'],'tablename'))
		{
			$modelid = isset($_POST['modelid']) && intval($_POST['modelid']) ? intval($_POST['modelid']) : $this->_app->showmessage('300','操作失败！');
			$info = $_POST['info'];
			content_model_model::model()->SET($info)->WHERE(array('modelid'=>$modelid))->update();
			$this->_cache_field($modelid);
			$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'closeCurrent','content_model_init');
		}
		else
		{
			$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
			$r = content_model_model::model()->WHERE(array('modelid'=>$modelid))->select(1);
			extract($r);
			include $this->view('content','model','edit');
		}
	}

	public function action_delete()
	{
		$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
		$model_cache = getcache('model','model');
		$model_table = $model_cache[$modelid]['tablename'];
		content_model_field::model()->WHERE(array('modelid'=>$modelid,'siteid'=>SITEID))->delete();
		content_model_model::drop_table($model_table);
		content_model_model::model()->WHERE(array('modelid'=>$modelid,'siteid'=>SITEID))->delete();

		$this->_app->showmessage('200','操作成功！',$this->_context->url('model::init@content'),'','content_model_init');
	}

	private function _cache()
	{
		require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
		//更新内容模型类：表单生成、入库、更新、输出
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype)
		{
			$cache_data = file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'content_'.$classtype.'.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue)
			{
				if(file_exists(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field.DIRECTORY_SEPARATOR.$classtype.'.php'))
				{
					$cache_data .= file_get_contents(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field.DIRECTORY_SEPARATOR.$classtype.'.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_model'.DIRECTORY_SEPARATOR.'cache_data'.DIRECTORY_SEPARATOR.'content_'.$classtype.'.php',$cache_data);
			@chmod(Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_model'.DIRECTORY_SEPARATOR.'cache_data'.DIRECTORY_SEPARATOR.'content_'.$classtype.'.php',0777);
		}
		//更新模型数据缓存
		$model_array = array();
		$datas = content_model_model::model()->WHERE(array('type'=>0))->select();
		foreach ($datas as $r)
		{
			if(!$r['disabled']) $model_array[$r['modelid']] = $r;
		}
		setcache('model', $model_array, 'model');
		return true;
	}

	private function _cache_field($modelid = 0)
	{
		$field_array = array();
		$basefield = content_model_field::model()->WHERE(array('modelid'=>0,'siteid'=>SITEID))->ORDER('listorder ASC')->select();
		$modelfield = content_model_field::model()->WHERE(array('modelid'=>$modelid,'disabled'=>0))->ORDER('listorder ASC')->select();
		$fields = array_merge($basefield, $modelfield);
		$fields = arr::sortbycol($fields,'listorder');
		foreach($fields as $_value)
		{
			$setting = json_decode($_value['setting'], true);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		setcache('model_field_'.$modelid,$field_array,'model');
		return true;
	}
	public function action_test()
	{
		$this->_cache_field(46);
	}
}
?>