<?php
class user_controller_role extends admin_class_controller
{
	function action_init()
	{
		$infos = user_model_role::model()->WHERE(array('siteid'=>SITEID))->ORDER('roleid DESC')->select();
		$this->_cache();
		include $this->view('user','role','init');
	}

	function action_add()
	{
		if($this->_context->isPOST() && user_model_role::model()->validate($_POST['info']))
		{
			$role_op = new user_class_roleop();
			if($role_op->checkname('',$_POST['info']['rolename'],SITEID,'add'))
			{
				$this->_app->showmessage('300','角色名称重复啦?');
			}
			$_POST['info']['siteid'] = SITEID;
			$insert_id = user_model_role::model()->FIELDVALUE($_POST['info'])->insert();
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('role::init@user'),'closeCurrent','user_role_init');
			}
		}
		else
		{
			$sitelist = getcache('sitelist','admin');
			$siteinfo = $sitelist[SITEID];
			include $this->view('user','role','add');
		}
	}

	function action_edit()
	{
		if($this->_context->isPOST() && user_model_role::model()->validate($_POST['info']))
		{
			$_POST['roleid'] = intval($_POST['roleid']);
			$role_op = new user_class_roleop();
			if($role_op->checkname($_POST['roleid'],$_POST['info']['rolename'],SITEID,'edit'))
			{
				$this->_app->showmessage('300','角色名称重复啦?');
			}
			user_model_role::model()->SET($_POST['info'])->WHERE(array('roleid'=>$_POST['roleid']))->update();
			$this->_app->showmessage('200','操作成功！',$this->_context->url('role::init@user'),'closeCurrent','user_role_init');
		}
		else
		{
			$info = user_model_role::model()->WHERE(array('roleid'=>intval($_GET['roleid'])))->select(1);
			extract($info);	
			include $this->view('user','role','edit');
		}
	}

	function action_delete()
	{
		$roleid = intval($_GET['roleid']);
		if($roleid == '1') $this->_app->showmessage('300','该对象不能被删除');
		user_model_role::model()->WHERE(array('roleid'=>$roleid))->delete();
		user_model_rolepriv::model()->WHERE(array('roleid'=>$roleid))->delete();
		$this->_app->showmessage('200','操作成功！',$this->_context->url('role::init@user'),'','user_role_init');
	}

	/**
	 * 更新角色状态
	 */
	function action_changeStatus()
	{
		$roleid = intval($_GET['roleid']);
		$disabled = intval($_GET['disabled']);
		user_model_role::model()->SET(array('disabled'=>$disabled))->WHERE(array('roleid'=>$roleid))->update();
		$this->_cache();
		$this->_app->showmessage('200','操作成功！',$this->_context->url('role::init@user'),'','user_role_init');
	}

	/**
	 * 角色缓存
	 */
	private function _cache()
	{
		$infos = user_model_role::model()->FIELD('siteid,roleid,rolename')->WHERE(array('disabled'=>'0'))->ORDER('roleid ASC,siteid ASC')->select();
		$siteid_role = $role = array();
		foreach ($infos as $info)
		{
			if($info['siteid'] == SITEID)
			{
				$role[$info['roleid']] = $info['rolename'];
			}
			$siteid_role[$info['roleid']] = array('rolename' => $info['rolename'], 'siteid' => $info['siteid']);
		}
		setcache('role_'.SITEID,$role,'user');
		setcache('role_site',$siteid_role,'user');
		return $infos;
	}
}
?>