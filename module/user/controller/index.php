<?php
class user_controller_index extends user_class_controller
{
	public function action_init()
	{
		include $this->view('user','index','init',ROUTE_S);
	}

	public function action_login()
	{
		if($this->_context->isPOST() && user_model_user::model()->validate($_POST,'code'))
		{
			$rtime = admin_model_times::model()->get_one(array('email'=>$_POST['email']));
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
            	$this->_app->showmessage('300','账户不存在',$this->_context->url('index::login@user'));
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
	                showmessage('300','密码错误，您还有'.$times.'次尝试机会！', url('index::login@user'));
            	}
            	if($rtime)
            	{
            		admin_model_times::model()->delete(array('email'=>$_POST['email']));
            	}

            	//如果用户被锁定
				if($r['islock'])
				{
					showmessage('300','该用户已被锁定，请联系管理员解锁！', url('index::login@user'));
				}

				$updateArr = array('lastloginip'=>ip(), 'lastlogintime'=>SYS_TIME);

				//vip过期，更新vip和会员组
				if($r['overduedate'] < SYS_TIME)
				{
					$updateArr['vip'] = 0;
				}

				//检查用户积分，更新新用户组，如果该用户组不允许自助升级则不进行该操作		
				if($r['point'] >= 0 && empty($r['vip']))
				{
					$grouplist = getcache('grouplist_'.$r['siteid'],'user');
					if(!empty($grouplist[$r['groupid']]['allowupgrade']))
					{	
						$check_groupid = $this->_get_usergroup_bypoint($r['point'],$r['siteid']);
						if($check_groupid != $r['groupid'])
						{
							$updatearr['groupid'] = $groupid = $check_groupid;
						}
					}
				}

            	user_model_user::model()->update($updateArr,array('userid'=>$r['userid']));

            	$x_auth_key = md5(Next::config('system', 'auth_key','29HTvKg84Veg8VtDdKbs').$_SERVER['HTTP_USER_AGENT']);
            	$x_auth = sys_auth($r['userid']."\t".$r['password'], 'ENCODE', $x_auth_key);

            	$_SESSION['userid'] = $r['userid'];
            	$_SESSION['email'] = $r['email'];
	            $_SESSION['roleid'] = implode(',', normalize($r['roleid']));
				$_SESSION['groupid'] = $r['groupid'];
				$_SESSION['siteid'] = $r['siteid'];
				$_SESSION['realname'] = $r['realname'];
				$_SESSION['auth'] = $x_auth;
                header('location:'.$this->_context->url('index::init@user'));
            }
		}
		else
		{
			$siteInfo = $this->get_siteinfo();
			$title = '会员登录入口-'.$siteInfo['name'];
			include $this->view('user','index','login',ROUTE_S);
		}
	}
}
?>