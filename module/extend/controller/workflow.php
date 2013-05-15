<?php
class extend_controller_workflow extends admin_class_controller
{
	public function action_init()
	{
		$data = extend_model_workflow::model()->WHERE(array('siteid'=>SITEID))->select();
		$this->_cache();
		include $this->view('extend','workflow','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() && extend_model_workflow::model()->validate($_POST['info']))
		{
			$_POST['info']['siteid'] = SITEID;
			$_POST['info']['workname'] = safe_replace($_POST['info']['workname']);
			$setting[1] = isset($_POST['checkadmin1']) ? $_POST['checkadmin1'] : null;
			$setting[2] = isset($_POST['checkadmin2']) ? $_POST['checkadmin2'] : null;
			$setting[3] = isset($_POST['checkadmin3']) ? $_POST['checkadmin3'] : null;
			$setting[4] = isset($_POST['checkadmin4']) ? $_POST['checkadmin4'] : null;
			$setting[5] = isset($_POST['checkadmin5']) ? $_POST['checkadmin5'] : null;
			$setting['nocheck_users'] = isset($_POST['nocheck_users']) ? $_POST['nocheck_users'] : null;
			$setting = serialize($setting);
			$_POST['info']['setting'] = $setting;
			$insert_id = extend_model_workflow::model()->FIELDVALUE($_POST['info'])->insert();
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('workflow::init@extend'),'closeCurrent','extend_workflow_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$admin_data = array();
			$result = user_model_user::model()->WHERE(array('siteid'=>SITEID))->select();
			foreach($result as $_value)
			{
				if($_value['roleid']==1) continue;
				$admin_data[$_value['userid']] = $_value['realname'] ? $_value['realname'] : $_value['nickname'];
			}
			include $this->view('extend','workflow','add');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST() && extend_model_workflow::model()->validate($_POST['info']))
		{
			$workflowid = intval($_POST['workflowid']);
			$_POST['info']['workname'] = safe_replace($_POST['info']['workname']);
			$setting[1] = isset($_POST['checkadmin1']) ? $_POST['checkadmin1'] : null;
			$setting[2] = isset($_POST['checkadmin2']) ? $_POST['checkadmin2'] : null;
			$setting[3] = isset($_POST['checkadmin3']) ? $_POST['checkadmin3'] : null;
			$setting[4] = isset($_POST['checkadmin4']) ? $_POST['checkadmin4'] : null;
			$setting[5] = isset($_POST['checkadmin5']) ? $_POST['checkadmin5'] : null;
			$setting['nocheck_users'] = isset($_POST['nocheck_users']) ? $_POST['nocheck_users'] : null;
			$setting = serialize($setting);
			$_POST['info']['setting'] = $setting;
			extend_model_workflow::model()->SET($_POST['info'])->WHERE(array('workflowid'=>$workflowid))->update();
			$this->_app->showmessage('200','操作成功！',$this->_context->url('workflow::init@extend'),'closeCurrent','extend_workflow_init');
		}
		else
		{
			$workflowid = isset($_GET['workflowid']) && intval($_GET['workflowid']) ? intval($_GET['workflowid']) : $this->_app->showmessage('300','操作失败！');
			$admin_data = array();
			$result = user_model_user::model()->WHERE(array('siteid'=>SITEID))->select();
			foreach($result as $_value)
			{
				if($_value['roleid']==1) continue;
				$admin_data[$_value['userid']] = $_value['realname'] ? $_value['realname'] : $_value['nickname'];
			}
			$r = extend_model_workflow::model()->WHERE(array('workflowid'=>$workflowid))->select(1);
			extract($r);
			$setting = unserialize($setting);
			$checkadmin1 = $this->_implode_ids($setting[1]);
			$checkadmin2 = $this->_implode_ids($setting[2]);
			$checkadmin3 = $this->_implode_ids($setting[3]);
			$checkadmin4 = $this->_implode_ids($setting[4]);
			$checkadmin5 = $this->_implode_ids($setting[5]);
			$nocheck_users = $this->_implode_ids($setting['nocheck_users']);
			include $this->view('extend','workflow','edit');
		}
	}

	public function action_view()
	{
			$workflowid = isset($_GET['workflowid']) && intval($_GET['workflowid']) ? intval($_GET['workflowid']) : $this->_app->showmessage('300','操作失败！');
			$admin_data = array();
			$result = user_model_user::model()->select();
			foreach($result as $_value)
			{
				if($_value['roleid']==1) continue;
				$admin_data[$_value['realname']] = $_value['realname'] ? $_value['realname'] : $_value['nickname'];
			}
			$r = extend_model_workflow::model()->WHERE(array('workflowid'=>$workflowid))->select(1);
			extract($r);
			$setting = unserialize($setting);

			$checkadmin1 = $this->_implode_ids($setting[1],true,'、');
			$checkadmin2 = $this->_implode_ids($setting[2],true,'、');
			$checkadmin3 = $this->_implode_ids($setting[3],true,'、');
			$checkadmin4 = $this->_implode_ids($setting[4],true,'、');
			$checkadmin5 = $this->_implode_ids($setting[5],true,'、');
			include $this->view('extend','workflow','view');
	}

	public function action_delete()
	{
		$_GET['workflowid'] = intval($_GET['workflowid']);
		extend_model_workflow::model()->WHERE(array('workflowid'=>$_GET['workflowid']))->delete();
		$this->_app->showmessage('200','操作成功！',$this->_context->url('workflow::init@extend'),'','extend_workflow_init');
	}

	private function _cache()
	{
		$datas = array();
		$workflow_datas =  extend_model_workflow::model()->WHERE(array('siteid'=>SITEID))->select();
		foreach($workflow_datas as $_k=>$_v)
		{
			$datas[$_v['workflowid']] = $_v;
		}
		setcache('workflow_'.SITEID,$datas,'extend');
		return true;
	}

	/**
	 * 用逗号分隔数组
	 */
	private function _implode_ids($array, $showusername = false, $flags = ',')
	{
		if(empty($array)) return true;
		$length = strlen($flags);
		$userop = new user_class_userop();
		$string = '';
		foreach($array as $_v)
		{
			if($showusername)
			{
				$username = $userop->get_userinfo($_v,'realname');
				$string .= $username.$flags;
			}
			else
			{
				$string .= $_v.$flags;
			}
		}
		return substr($string,0,-$length);
	}
}
?>