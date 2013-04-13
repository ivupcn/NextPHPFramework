<?php
class user_controller_group extends admin_class_controller
{
	function action_init()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$user_group_list = user_model_group::model()->listinfo(array('siteid'=>ROUTE_S), 'sort ASC', $page, 20);
		//TODO 此处循环中执行sql，会严重影响效率，稍后考虑在memebr_group表中加入会员数字段和统计会员总数功能解决。
		foreach ($user_group_list as $k=>$v)
		{
			$usernum = user_model_user::model()->count(array('groupid'=>$v['groupid']));
			$user_group_list[$k]['usernum'] = $usernum;
		}
		$pages = user_model_group::model()->pages;
		$this->_updatecache();
		include $this->view('user','group','init');
	}

	function action_add() {
		if($this->_context->isPOST())
		{
			$info = array();
			if(!$this->_checkname($_POST['info']['name']))
			{
				showmessage('300', '会员组名称已经存在！');
			}
			$info = $_POST['info'];
			$info['allowpost'] = isset($info['allowpost']) ? 1 : 0;
			$info['allowupgrade'] = isset($info['allowupgrade']) ? 1 : 0;
			$info['allowpostverify'] = isset($info['allowpostverify']) ? 1 : 0;
			$info['allowsendmessage'] = isset($info['allowsendmessage']) ? 1 : 0;
			$info['allowattachment'] = isset($info['allowattachment']) ? 1 : 0;
			$info['allowsearch'] = isset($info['allowsearch']) ? 1 : 0;
			$info['allowvisit'] = isset($info['allowvisit']) ? 1 : 0;
			$info['spamcertification'] = isset($info['spamcertification']) ? 1 : 0;
			$info['siteid'] = ROUTE_S;
			
			$insert_id = user_model_group::model()->insert($info,true);
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('group::init@user'),'closeCurrent','user_group_init');
			}
		}
		else
		{
			include $this->view('user','group','add');
		}
		
	}

	function action_edit()
	{
		if($this->_context->isPOST())
		{
			$info = array();
			$info = $_POST['info'];

			$info['allowpost'] = isset($info['allowpost']) ? 1 : 0;
			$info['allowupgrade'] = isset($info['allowupgrade']) ? 1 : 0;
			$info['allowpostverify'] = isset($info['allowpostverify']) ? 1 : 0;
			$info['allowsendmessage'] = isset($info['allowsendmessage']) ? 1 : 0;
			$info['allowattachment'] = isset($info['allowattachment']) ? 1 : 0;
			$info['allowsearch'] = isset($info['allowsearch']) ? 1 : 0;
			$info['allowvisit'] = isset($info['allowvisit']) ? 1 : 0;
			$info['spamcertification'] = isset($info['spamcertification']) ? 1 : 0;
			user_model_group::model()->update($info, array('groupid'=>$info['groupid']));
			
			$this->_app->showmessage('200','操作成功！',$this->_context->url('group::init@user'),'closeCurrent','user_group_init');
		}
		else
		{					
			$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : $this->_app->showmessage('300','非法参数！');
			
			$groupinfo = user_model_group::model()->get_one(array('groupid'=>$groupid));
			include $this->view('user','group','edit');	
		}
	}

	function action_sort()
	{		
		if($this->_context->isPOST())
		{
			foreach($_POST['sort'] as $k=>$v)
			{
				user_model_group::model()->update(array('sort'=>$v), array('groupid'=>$k));
			}
			
			$this->_app->showmessage('200','操作成功！',$this->_context->url('group::init@user'),'','user_group_init');
		} else {
			$this->_app->showmessage('300', '操作失败！');
		}
	}

	function action_delete()
	{	
		$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : $this->_app->showmessage('300', '参数错误！');
		user_model_group::model()->delete(array('groupid'=>$groupid));
		$this->_app->showmessage('200','操作成功！',$this->_context->url('group::init@user'),'','user_group_init');
	}

	/**
	 * 检查用户名是否合法
	 * @param string $name
	 */
	private function _checkname($name = NULL)
	{
		if(empty($name)) return false;
		if (user_model_group::model()->get_one(array('name'=>$name,'siteid'=>ROUTE_S),'groupid'))
		{
			return false;
		}
		return true;
	}

	private function _updatecache() {
		$grouplist = user_model_group::model()->listinfo(array('siteid'=>ROUTE_S), '', 1, 1000, 'groupid');
		setcache('grouplist_'.ROUTE_S, $grouplist,'user');
	}
}
?>