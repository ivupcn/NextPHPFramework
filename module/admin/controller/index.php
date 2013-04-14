<?php
class admin_controller_index extends admin_class_controller
{
	public function action_init()
	{
		$userid = $_SESSION['userid'];
        $email = $_SESSION['email'];
        $roles = getcache('role_'.ROUTE_S,'user');
		$roleid = explode(',',$_SESSION['roleid']);
		$rolename = array();
		foreach($roleid as $roleid)
		{
            if($roleid == 1)
            {
                $rolename[] = '超级管理员';
            }
            else
            {
                $rolename[] = $roles[$roleid];
            }
		}
		$rolename = implode(',', $rolename);
        $site = new admin_class_sites();
        $sitelist = $site->get_list($_SESSION['roleid']);
        $currentsite = $site->get_siteinfo(ROUTE_S);
        $menu_arr = $this->admin_menu(1);
        $init_left_menu = $this->admin_menu(4);
		include $this->view('admin','index','init');
	}
	
	public function action_login()
	{
		if($this->_context->isPOST() && user_model_user::model()->validate($_POST))
		{
			$rtime = admin_model_times::model()->get_one(array('email'=>$_POST['email'],'isadmin'=>1));
			$maxloginfailedtimes = Next::config('system','maxloginfailedtimes','8');
			if($rtime['times'] > $maxloginfailedtimes)
			{
                $minute = 60-floor((SYS_TIME-$rtime['logintime'])/60);
                $this->_app->showmessage('300','密码重试次数太多，请过'.$minute.'分钟后重新登录！');
            }
            //查询帐号
            $r = user_model_user::model()->get_one(array('email'=>$_POST['email']));
            if(!$r)
            {
            	$this->_app->showmessage('300','账户不存在',$this->_context->url('index::login@admin'));
            }
            else
            {
            	$password = md5(md5(trim($_POST['password'])).$r['encrypt']);
            	if($r['password'] != $password) 
            	{
                	$ip = ip();
	                if($rtime && $rtime['times'] < $maxloginfailedtimes)
	                {
	                    $times = $maxloginfailedtimes-intval($rtime['times']);
	                    admin_model_times::model()->update(array('ip'=>$ip,'isadmin'=>1,'times'=>'+=1'),array('email'=>$_POST['email']));
	                }
	                else
	                {
	                	if($rtime)
	                	{
	                		admin_model_times::model()->delete(array('email'=>$_POST['email'],'isadmin'=>1));
	                	}
	                    admin_model_times::model()->insert(array('email'=>$_POST['email'],'ip'=>$ip,'isadmin'=>1,'logintime'=>SYS_TIME,'times'=>1));
	                    $times = $maxloginfailedtimes;
	                }
	                $this->_app->showmessage('300','密码错误，您还有'.$times.'次尝试机会！', $this->_context->url('index::login@admin'));
            	}
            	if($rtime)
            	{
            		admin_model_times::model()->delete(array('email'=>$_POST['email']));
            	}
            	user_model_user::model()->update(array('lastloginip'=>ip(),'lastlogintime'=>SYS_TIME),array('userid'=>$r['userid']));
            	$_SESSION['userid'] = $r['userid'];
            	$_SESSION['email'] = $r['email'];
	            $_SESSION['roleid'] = implode(',', normalize($r['roleid']));
	            $_SESSION['siteid'] = $r['siteid'];
                $_SESSION['groupid'] = $r['groupid'];
                $this->_context->set_cookie('siteid',ROUTE_S,0);
                $this->_app->showmessage('200','登录成功！', $this->_context->url('index::init#'.$r['siteid'].'@admin'));
            }
		}
		else
		{
			include $this->view('admin','index','login');
		}
	}

	public function action_logout()
	{
        session_unset();
		session_destroy();
        Header('Location:'.$this->_context->url('index::login@admin'));
	}

	public function action_menuLeft()
    {
        $menuid = intval($_GET['menuid']);
        $datas = $this->admin_menu($menuid);
        include $this->view('admin','index','left');
    }

    public function action_main()
    {
        echo 'main';
    }

    /**
    * 设置站点ID SESSION
    */
    public function action_setSiteid()
    {
        $_SESSION['siteid'] = ROUTE_S;
        $this->_context->set_cookie('siteid',ROUTE_S,0);
        exit('1');
    }
}
?>
