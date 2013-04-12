<?php
class extend_controller_urlrule extends admin_class_controller
{
	public function action_init()
	{
		$page = isset($_POST['pageNum']) ? intval($_POST['pageNum']) : '1';
		$data = extend_model_urlrule::model()->listinfo(array('siteid'=>$this->get_siteid()), '', $page, 20,'','','',1);
		$pages = extend_model_urlrule::model()->pages;
		$this->_cache();
		include $this->view('extend','urlrule','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() && extend_model_urlrule::model()->validate($_POST['info']))
		{
			$insert_id = extend_model_urlrule::model()->insert($_POST['info'],true);
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('urlrule::init@extend'),'closeCurrent','extend_urlrule_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$modules_arr = admin_model_module::model()->select('', 'module,name');
			$modules = array();
			foreach ($modules_arr as $r)
			{
				$modules[$r['module']] = $r['name'];
			}
			include $this->view('extend','urlrule','add');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST() && extend_model_urlrule::model()->validate($_POST['info']))
		{
			$urlruleid = isset($_POST['urlruleid']) && intval($_POST['urlruleid']) ? intval($_POST['urlruleid']) : $this->_app->showmessage('300','操作失败！');
			 extend_model_urlrule::model()->update($_POST['info'],array('urlruleid'=>$urlruleid));
			 $this->_app->showmessage('200','操作成功！',$this->_context->url('urlrule::init@extend'),'closeCurrent','extend_urlrule_init');
		}
		else
		{
			$urlruleid = isset($_GET['urlruleid']) && intval($_GET['urlruleid']) ? intval($_GET['urlruleid']) : $this->_app->showmessage('300','操作失败！');
			$r = extend_model_urlrule::model()->get_one(array('urlruleid'=>$urlruleid));
			extract($r);
			$modules_arr = admin_model_module::model()->select('', 'module,name');
			$modules = array();
			foreach ($modules_arr as $r)
			{
				$modules[$r['module']] = $r['name'];
			}
			include $this->view('extend','urlrule','edit');
		}
	}

	public function action_delete() {
		$urlruleid = isset($_GET['urlruleid']) && intval($_GET['urlruleid']) ? intval($_GET['urlruleid']) : $this->_app->showmessage('300','操作失败！');
		extend_model_urlrule::model()->delete(array('urlruleid'=>$urlruleid));
		$this->_app->showmessage('200','操作成功！',$this->_context->url('urlrule::init@extend'),'','extend_urlrule_init');
	}

	/**
	 * 更新URL规则
	 */
	public function _cache() {
		$datas = extend_model_urlrule::model()->select('','*','','','','urlruleid');
		$basic_data = array();
		foreach($datas as $roleid=>$r)
		{
			$basic_data[$roleid] = $r['urlrule'];;
		}
		setcache('urlrules_detail_'.$this->get_siteid(),$datas,'extend');
		setcache('urlrules_'.$this->get_siteid(),$basic_data,'extend');
	}
}
?>