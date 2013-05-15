<?php
class content_controller_field extends admin_class_controller
{
	public function action_init()
	{
		$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
		$basefield = content_model_field::model()->WHERE(array('modelid'=>0,'siteid'=>SITEID))->ORDER('listorder ASC')->select();
		$modelfield = content_model_field::model()->WHERE(array('modelid'=>$modelid))->ORDER('listorder ASC')->select();
		$field_arr = array_merge($basefield, $modelfield);
		$field_arr = arr::sortbycol($field_arr,'listorder');
		require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
		include $this->view('content','field','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST())
		{
			$model_cache = getcache('model','model');
			$modelid = $_POST['info']['modelid'] = intval($_POST['info']['modelid']);
			$model_table = $model_cache[$modelid]['tablename'];
			$config = Next::config('database','default');
			$tablename = $config['tablepre'].'post_'.SITEID.'_'.$model_table;
			$field = $_POST['info']['field'];
			$minlength = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field_type.DIRECTORY_SEPARATOR.'config.php';
			if(isset($_POST['setting']['fieldtype']))
			{
				$field_type = $_POST['setting']['fieldtype'];
			}
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'add.php';
			$_POST['info']['setting'] = serialize($_POST['setting']);
			$_POST['info']['siteid'] = SITEID;
			content_model_field::model()->FIELDVALUE($_POST['info'])->insert();
			$this->_cache_field($modelid);
			$this->_app->showmessage('200','操作成功！',$this->_context->url('field::init@content','modelid/'.$modelid),'closeCurrent','content_field_init');
		}
		else
		{
			$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
			$f_datas = content_model_field::model()->FIELD('field,name')->WHERE(array('modelid'=>$modelid))->ORDER('listorder ASC')->select();
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
			include $this->view('content','field','add');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST())
		{
			$model_cache = getcache('model','model');
			$modelid = $_POST['info']['modelid'] = intval($_POST['info']['modelid']);
			$model_table = $model_cache[$modelid]['tablename'];
			$config = Next::config('database','default');
			$tablename = $config['tablepre'].'post_'.SITEID.'_'.$model_table;
			$field = $_POST['info']['field'];
			$minlength = $_POST['info']['minlength'] ? $_POST['info']['minlength'] : 0;
			$maxlength = $_POST['info']['maxlength'] ? $_POST['info']['maxlength'] : 0;
			$field_type = $_POST['info']['formtype'];
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$field_type.DIRECTORY_SEPARATOR.'config.php';
			if(isset($_POST['setting']['fieldtype']))
			{
				$field_type = $_POST['setting']['fieldtype'];
			}
			$oldfield = $_POST['oldfield'];
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'edit.php';
			$_POST['info']['setting'] = serialize($_POST['setting']);
			$fieldid = intval($_POST['fieldid']);
			content_model_field::model()->SET($_POST['info'])->WHERE(array('fieldid'=>$fieldid,'siteid'=>SITEID))->update();
			$this->_cache_field($modelid);
			$this->_app->showmessage('200','操作成功！',$this->_context->url('field::init@content','modelid/'.$modelid),'closeCurrent','content_field_init');
		}
		else
		{
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.'field.php';
			$modelid = intval($_GET['modelid']);
			$fieldid = intval($_GET['fieldid']);
			$m_r = content_model_model::model()->WHERE(array('modelid'=>$modelid))->select(1);
			$r = content_model_field::model()->WHERE(array('fieldid'=>$fieldid))->select(1);
			extract($r);
			require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$formtype.DIRECTORY_SEPARATOR.'config.php';
			$setting = unserialize($setting);
			ob_start();
			include Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$formtype.DIRECTORY_SEPARATOR.'field_edit_form.php';
			$form_data = ob_get_contents();
			ob_end_clean();
			header("Cache-control: private");
			include $this->view('content','field','edit');
		}
	}

	public function action_delete()
	{
		$fieldid = intval($_GET['fieldid']);
		$r = content_model_field::model()->WHERE(array('fieldid'=>$_GET['fieldid'],'siteid'=>SITEID))->select(1);
		//必须放在删除字段前、在删除字段部分，重置了 tablename
		content_model_field::model()->WHERE(array('fieldid'=>$_GET['fieldid'],'siteid'=>SITEID))->delete();

		$model_cache = getcache('model','model');
		$modelid = intval($_GET['modelid']);
		$model_table = $model_cache[$modelid]['tablename'];
		$config = Next::config('database','default');
		$tablename = $config['tablepre'].'post_'.SITEID.'_'.$model_table;
		content_model_field::drop_field($tablename,$r['field']);
		$this->_app->showmessage('200','操作成功！',$this->_context->url('field::init@content','modelid/'.$modelid),'','content_field_init');
	}

	public function action_disabled()
	{
		$fieldid = intval($_GET['fieldid']);
		$disabled = $_GET['disabled'] ? 0 : 1;
		content_model_field::model()->SET(array('disabled'=>$disabled))->WHERE(array('fieldid'=>$fieldid,'siteid'=>SITEID))->update();
		$modelid = $_GET['modelid'];
		$this->_cache_field($modelid);
		$this->_app->showmessage('200','操作成功！',$this->_context->url('field::init@content','modelid/'.$modelid),'','content_field_init');
	}

	public function action_setting()
	{
		$fieldtype = $_GET['fieldtype'];
		require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$fieldtype.DIRECTORY_SEPARATOR.'config.php';
		ob_start();
		include Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'content'.DIRECTORY_SEPARATOR.'field'.DIRECTORY_SEPARATOR.$fieldtype.DIRECTORY_SEPARATOR.'field_add_form.php';
		$data_setting = ob_get_contents();
		ob_end_clean();
		$settings = array('field_basic_table'=>$field_basic_table,'field_minlength'=>$field_minlength,'field_maxlength'=>$field_maxlength,'field_allow_search'=>$field_allow_search,'field_allow_fulltext'=>$field_allow_fulltext,'field_allow_isunique'=>$field_allow_isunique,'setting'=>$data_setting);
		echo serialize($settings);
		return true;
	}

	public function action_listorder()
	{
		if($this->_context->isPOST())
		{
			foreach($_POST['listorders'] as $id => $listorder)
			{
				content_model_field::model()->SET(array('listorder'=>$listorder))->WHERE(array('fieldid'=>$id))->update();
			}
			$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
			$this->_cache_field($modelid);
			$this->_app->showmessage('200','操作成功！',$this->_context->url('field::init@content','modelid/'.$modelid),'','content_field_init');
		}
		else
		{
			$this->_app->showmessage('300','操作失败！');
		}
	}

	public function action_priview()
	{
		$modelid = isset($_GET['modelid']) && intval($_GET['modelid']) ? intval($_GET['modelid']) : $this->_app->showmessage('300','操作失败！');
		require Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_model'.DIRECTORY_SEPARATOR.'cache_data'.DIRECTORY_SEPARATOR.'content_form.php';
		$content_form = new content_form($modelid);
		$forminfos = $content_form->get();
		include $this->view('content','field','priview');
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
			$setting = unserialize($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		setcache('model_field_'.$modelid,$field_array,'model');
		return true;
	}
}
?>