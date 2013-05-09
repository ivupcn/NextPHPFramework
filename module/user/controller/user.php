<?php
class user_controller_user extends admin_class_controller
{
	function action_init()
	{
		$page = isset($_POST['pageNum']) ? intval($_POST['pageNum']) : '1';
		$infos = user_model_user::model()->WHERE(array('siteid'=>SITEID))->ORDER('userid ASC')->PAGE(array('page'=>$page))->select();
		$pages = user_model_user::model()->pages;
		$roles = getcache('role_'.SITEID,'user');
		$groups = getcache('grouplist_'.SITEID,'user');
		include $this->view('user','user','init');
	}

	function action_add()
	{
		if($this->_context->isPOST() && user_model_user::model()->validate($_POST['info'],'code'))
		{
			$info = array();
			$userop = new user_class_userop();
			if(!$userop->checkemail($_POST['info']['email']))
			{
				$this->_app->showmessage('300','该邮箱已经存在');
			}
			$info = $_POST['info'];
			$passwordinfo = password($info['password']);
			$info['password'] = $passwordinfo['password'];
			$info['encrypt'] = $passwordinfo['encrypt'];
			$info['siteid'] = SITEID;
			$info['roleid'] = implode(',',$info['roleid']);
			$admin_fields = array('email', 'password', 'encrypt','roleid', 'groupid','realname','siteid');
			foreach ($info as $k=>$value)
			{
				if (!in_array($k, $admin_fields))
				{
					unset($info[$k]);
				}
			}
			$insert_id = user_model_user::model()->FIELDVALUE($info)->insert();
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功',$this->_context->url('user::init@user'),'closeCurrent','user_user_init');
			}
		}
		else
		{
			$roles = getcache('role_'.SITEID,'user');
			$groups = getcache('grouplist_'.SITEID,'user');
			include $this->view('user','user','add');
		}
	}

	function action_edit()
	{
		if($this->_context->isPOST())
		{
			$info = array();			
			$info = $_POST['info'];
			$userop = new user_class_userop();
			if(isset($info['password']) && !empty($info['password']))
			{
				$userop->edit_password($info['userid'], $info['password']);
			}
			$info['roleid'] = implode(',',$info['roleid']);
			$userid = $info['userid'];
			$admin_fields = array('email', 'roleid', 'groupid', 'realname', 'siteid');
			foreach ($info as $k=>$value)
			{
				if (!in_array($k, $admin_fields))
				{
					unset($info[$k]);
				}
			}
			user_model_user::model()->SET($info)->WHERE(array('userid'=>$userid))->update();
			$this->_app->showmessage('200','操作成功',$this->_context->url('user::init@user'),'closeCurrent','user_user_init');
		}
		else
		{					
			$info = user_model_user::model()->WHERE(array('userid'=>$_GET['userid']))->select(1);
			extract($info);	
			$roles = getcache('role_'.SITEID,'user');
			$groups = getcache('grouplist_'.SITEID,'user');	
			include $this->view('user','user','edit');
		}
	}

	/**
	 * 删除管理员
	 */
	function action_delete() {
		$userid = intval($_GET['userid']);
		if($userid == '1') $this->_app->showmessage('300','该对象不能删除');
		user_model_user::model()->WHERE(array('userid'=>$userid))->delete();
		$this->_app->showmessage('200','会员删除成功');
	}

	/**
	* 角色成员
	*/
	function action_roleUser()
	{
		$roleid = intval($_GET['roleid']);
		$roles = getcache('role_'.SITEID,'user');
		$groups = getcache('grouplist_'.SITEID,'user');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : '1';
		$infos = user_model_user::model()->WHERE(array('roleid'=>array('FINDINSET',$roleid)))->select();
		include $this->view('user','user','roleuser');
	}


	/*
	 * 编辑用户信息
	 */
	function action_editInfo()
	{
		$userid = $_SESSION['userid'];
		if($this->_context->isPOST())
		{
			$admin_fields = array('realname');
			$info = array();
			$info = $_POST['info'];
			foreach ($info as $k=>$value)
			{
				if (!in_array($k, $admin_fields))
				{
					unset($info[$k]);
				}
			}
			user_model_user::model()->SET($info)->WHERE(array('userid'=>$userid))->update();
			$this->_app->showmessage('200','操作成功',$this->_context->referer());			
		}
		else
		{
			$info = user_model_user::model()->WHERE(array('userid'=>$userid))->select(1);
			extract($info);
			include $this->view('user','user','editinfo');			
		}
	}

	/**
	 * 管理员自助修改密码
	 */
	function action_editPwd()
	{
		$userid = $_SESSION['userid'];
		if($this->_context->isPOST())
		{
			$r = user_model_user::model()->FIELD('password,encrypt')->WHERE(array('userid'=>$userid))->select(1);
			if (password($_POST['old_password'],$r['encrypt']) !== $r['password'] ) $this->_app->showmessage('300','旧密码输入错误',$this->_context->referer());
			if(isset($_POST['new_password']) && !empty($_POST['new_password']))
			{
				$userop = new user_class_userop();
				$userop->edit_password($userid, $_POST['new_password']);
			}
			$this->_app->showmessage('301','密码修改该成功,请使用新密码重新登录');			
		}
		else 
		{
			$info = user_model_user::model()->WHERE(array('userid'=>$userid))->select(1);
			extract($info);
			include $this->view('user','user','editpwd');	
		}

	}
}
?>