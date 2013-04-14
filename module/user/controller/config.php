<?php
class user_controller_config extends admin_class_controller
{
	function action_init()
	{
		if($this->_context->isPOST())
		{
			$info = new_addslashes($_POST['info']);
			setcache('user_config_'.SITEID,$info,'user');
			$this->_app->showmessage('200','操作成功！',$this->_context->url('config::init@user'),'','user_config_init');
		}
		else
		{
			$user_config = getcache('user_config_'.SITEID,'user');
			$grouplist = getcache('grouplist_'.SITEID,'user');
			include $this->view('user','config','init');
		}
	}
}
?>