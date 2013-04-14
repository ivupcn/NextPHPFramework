<?php
class oa_controller_task extends admin_class_controller
{
	public function action_all()
	{
		$page = isset($_POST['pageNum']) && intval($_POST['pageNum']) ? intval($_POST['pageNum']) :1;
		$data = oa_model_task::model()->listinfo(array('siteid'=>SITEID), 'id DESC', $page, 20,'','','',1);
		$pages = oa_model_task::model()->pages;
		$userop = new user_class_userop();
		$category = getcache('category_oa_'.SITEID,'admin');
		include $this->view('oa','task','all');
	}

	public function action_intro()
	{
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
		$info = oa_model_task::model()->get_one(array('id'=>$id),'attachment,intro');
		$siteinfo = $this->get_siteinfo();
		include $this->view('oa','task','intro');
	}

	public function action_mycreate()
	{
		$page = isset($_POST['pageNum']) && intval($_POST['pageNum']) ? intval($_POST['pageNum']) :1;
		$data = oa_model_task::model()->listinfo(array('sponsor'=>$_SESSION['userid']), 'id DESC', $page, 20,'','','',1);
		$pages = oa_model_task::model()->pages;
		$userop = new user_class_userop();
		$category = getcache('category_oa_'.SITEID,'admin');
		$workflow = getcache('workflow_'.SITEID,'extend');
		include $this->view('oa','task','mycreate');
	}

	public function action_my()
	{
		$page = isset($_POST['pageNum']) && intval($_POST['pageNum']) ? intval($_POST['pageNum']) :1;
		$data = oa_model_task::model()->listinfo(array('assignedto'=>$_SESSION['userid']), 'status ASC, id DESC', $page, 20,'','','',1);
		$pages = oa_model_task::model()->pages;
		$userop = new user_class_userop();
		$category = getcache('category_oa_'.SITEID,'admin');
		$this->looptask();
		include $this->view('oa','task','my');
	}

	public function action_group()
	{
		$page = isset($_POST['pageNum']) && intval($_POST['pageNum']) ? intval($_POST['pageNum']) :1;
		$groupid = $_SESSION['groupid'];
		$userid_arr = array();
		$group_user = user_model_user::model()->select(array('groupid'=>$groupid));
		foreach($group_user as $g)
		{
			$userid_arr[] = $g['userid'];
		}
		$userid_string = implode(',', $userid_arr);
		$sql = 'assignedto in ('.$userid_string.')';
		$data = oa_model_task::model()->listinfo($sql, 'id DESC', $page, 20,'','','',1);
		$pages = oa_model_task::model()->pages;
		$userop = new user_class_userop();
		$category = getcache('category_oa_'.SITEID,'admin');
		include $this->view('oa','task','group');
	}

	public function action_checklist()
	{
		$page = isset($_POST['pageNum']) && intval($_POST['pageNum']) ? intval($_POST['pageNum']) :1;
		$data = extend_model_workcheck::model()->listinfo('', 'fromid ASC', $page, 20,'','','',1);
		$pages = extend_model_workcheck::model()->pages;
		$workflows = getcache('workflow_'.SITEID,'extend');
		$category = getcache('category_oa_'.SITEID,'admin');
		$userop = new user_class_userop();
		include $this->view('oa','task','checklist');
	}

	public function action_assess()
	{
		$user = user_model_user::model()->select(array('siteid'=>SITEID),'userid,realname');
		include $this->view('oa','task','assess');
	}

	public function action_assessuser()
	{
		$tree = new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
	
		$result = admin_model_tag::model()->select(array('siteid'=>SITEID,'module'=>'oa','type'=>'task_category'),'*','','listorder ASC,id DESC');
		$array = array();
		foreach($result as $r)
		{
			$r['user_assess'] = '';
			$array[] = $r;
		}

		$str  = "<tr>
					<td>\$spacer\$tagname</td>
					\$user_assess
				</tr>";
		$tree->init($array);
		$assess = $tree->get_tree(0, $str);
		include $this->view('oa','task','assessuser');
	}

	public function action_publish()
	{
		if($this->_context->isPOST() && oa_model_task::model()->validate($_POST['info']))
		{
			$info = new_addslashes($_POST['info']);
			$info['siteid'] = SITEID;
			$info['sponsor'] = $_SESSION['userid'];
			$info['planstarttime'] = strtotime($info['planstarttime']);
			$info['planendtime'] = strtotime($info['planendtime']);
			$info['status'] = 1;
			if(isset($info['attachment']))
			{
				$info['attachment'] = array2string($info['attachment']);
			}
			if(isset($_POST['assignedto']) && !empty($_POST['assignedto']))
			{
				foreach($_POST['assignedto'] as $k => $v)
				{
					$info['assignedto'] = $v;
					if($info['assignedto'] == $_SESSION['userid'])
					{
						$info['status'] = 2;
						$info['actualstarttime'] = SYS_TIME;
					}
					$insert_id = oa_model_task::model()->insert($info,true);
				}
			}
			else
			{
				if($info['assignedto'] == $_SESSION['userid'])
				{
					$info['status'] = 2;
					$info['actualstarttime'] = SYS_TIME;
				}
				$insert_id = oa_model_task::model()->insert($info,true);
			}
			if($insert_id)
			{
				if($info['looptask'] != 'once')
				{
					oa_model_task::model()->update(array('loopfrom'=>$insert_id),array('id'=>$insert_id));
				}
				$this->_app->showmessage('200','操作成功！',$this->_context->url('task::mycreate@oa'),'closeCurrent','oa_task_mycreate');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$user = user_model_user::model()->select(array('siteid'=>SITEID));
			include $this->view('oa','task','publish');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST() && oa_model_task::model()->validate($_POST['info']))
		{
			$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->_app->showmessage('300','操作失败！');
			$info = new_addslashes($_POST['info']);
			$info['planstarttime'] = strtotime($info['planstarttime']);
			$info['planendtime'] = strtotime($info['planendtime']);
			oa_model_task::model()->update($info,array('id'=>$id));
			$this->_app->showmessage('200','操作成功！',$this->_context->url('task::mycreate@oa'),'closeCurrent','oa_task_mycreate');
		}
		else
		{
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
			$info = oa_model_task::model()->get_one(array('id'=>$id));
			$user = user_model_user::model()->select(array('siteid'=>SITEID));
			include $this->view('oa','task','edit');
		}
	}

	public function action_accepted()
	{
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
		oa_model_task::model()->update(array('status'=>2,'actualstarttime'=>SYS_TIME),array('id'=>$id));
		$this->_app->showmessage('200','操作成功！',$this->_context->url('task::my@oa'),'','oa_task_my');
	}

	public function action_delete()
	{
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
		oa_model_task::model()->delete(array('id'=>$id));
		$this->_app->showmessage('200','操作成功！',$this->_context->url('task::mycreate@oa'),'','oa_task_mycreate');
	}

	public function action_progress()
	{
		if($this->_context->isPOST())
		{
			$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->_app->showmessage('300','操作失败！');
			$progress = new_addslashes($_POST['info']);
			$string = array2string($progress);
			oa_model_task::model()->update(array('progress'=>$string),array('id'=>$id));
			$this->_app->showmessage('200','操作成功！',$this->_context->url('task::my@oa'),'closeCurrent','oa_task_my');
		}
		else
		{
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
			$info = oa_model_task::model()->get_one(array('id'=>$id));
			$progress = string2array($info['progress']);
			include $this->view('oa','task','progress');
		}
	}

	public function action_complete()
	{
		$category = getcache('category_oa_'.SITEID,'admin');
		if($this->_context->isPOST())
		{
			$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->_app->showmessage('300','操作失败！');
			$progress = new_addslashes($_POST['progress']);
			$string = array2string($progress);
			unset($progress_arr);

			$selfrated = intval($_POST['info']['selfrated']);
			$report = new_addslashes($_POST['info']['report']);
			$reportatt = isset($_POST['info']['attachment']) ?  array2string($_POST['info']['attachment']) : null;
			$info = oa_model_task::model()->get_one(array('id'=>$id));
			
			$setting = string2array($category[$info['tagid']]['setting']);
			if(isset($setting['workflowid']) && !empty($setting['workflowid']))
			{
				$workflows = getcache('workflow_'.SITEID,'extend');
				$workflow = $workflows[$setting['workflowid']];
				$workflow['setting'] = string2array($workflow['setting']);
				$nocheck_users = $workflow['setting']['nocheck_users'];
				$steps = $workflow['steps'];
				if(in_array($_SESSION['userid'], $nocheck_users))
				{
					$status = 99;
				}
				else
				{
					$status = 4;
					extend_model_workcheck::model()->insert(array('module'=>'oa','tagid'=>$info['tagid'],'fromid'=>$id,'title'=>$info['title'],'userid'=>$info['assignedto'],'steps'=>$steps));
				}
			}
			else
			{
				$status = 4;
			}

			oa_model_task::model()->update(array('progress'=>$string,'selfrated'=>$selfrated,'report'=>$report,'reportatt'=>$reportatt,'status'=>$status,'actualendtime'=>SYS_TIME),array('id'=>$id));
			$this->_app->showmessage('200','操作成功！',$this->_context->url('task::my@oa'),'closeCurrent','oa_task_my');
		}
		else
		{
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
			$info = oa_model_task::model()->get_one(array('id'=>$id));
			$progress = string2array($info['progress']);
			include $this->view('oa','task','complete');
		}
	}

	public function action_check()
	{
		if($this->_context->isPOST())
		{
			$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->_app->showmessage('300','操作失败！');
			$tagid = isset($_POST['tagid']) && intval($_POST['tagid']) ? intval($_POST['tagid']) : $this->_app->showmessage('300','操作失败！');
			$check = extend_model_workcheck::model()->get_one(array('module'=>'oa','tagid'=>$tagid,'fromid'=>$id));
			if(intval($_POST['result']) == 1)
			{
				if($check)
				{
					if($check['steps'] == $check['status'])
					{
						$compentedrated = isset($_POST['compentedrated']) && intval($_POST['compentedrated']) ? intval($_POST['compentedrated']) : 0;
						oa_model_task::model()->update(array('status'=>99,'compentedrated'=>$compentedrated),array('id'=>$id));
						extend_model_workcheck::model()->delete(array('module'=>'oa','tagid'=>$tagid,'fromid'=>$id));
					}
					elseif($check['steps'] > $check['status'])
					{
						++$check['status'];
						extend_model_workcheck::model()->update(array('status'=>$check['status']),array('module'=>'oa','tagid'=>$tagid,'fromid'=>$id));
					}
				}
			}
			else
			{
				oa_model_task::model()->update(array('status'=>3),array('id'=>$id));
				extend_model_workcheck::model()->delete(array('module'=>'oa','tagid'=>$tagid,'fromid'=>$id));
			}
			$this->_app->showmessage('200','操作成功！',$this->_context->url('task::checklist@oa'),'closeCurrent','oa_task_checklist');
		}
		else
		{
			$category = getcache('category_oa_'.SITEID,'admin');
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
			$info = oa_model_task::model()->get_one(array('id'=>$id));
			$progress = string2array($info['progress']);
			$userop = new user_class_userop();
			$siteinfo = $this->get_siteinfo();
			include $this->view('oa','task','check');
		}
	}

	public function action_timeline()
	{
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : 0;
		include $this->view('oa','task','timeline');
	}

	public function action_timelinedata()
	{
		$userid = isset($_GET['userid']) && intval($_GET['userid']) ? intval($_GET['userid']) : 0;
		header('Content-Type: text/xml');
		$xml = '<?xml version="1.0" encoding="utf-8"?><data>';
		if($userid)
		{
			$data = oa_model_task::model()->select(array('assignedto'=>$userid,'status'=>99));
		}
		else
		{
			$data = oa_model_task::model()->select(array('status'=>99));
		}
		foreach($data as $r)
		{
			$xml .= '<event start="'.gmdate('l M d Y H:i:s',$r['actualstarttime']).'" end="'.gmdate('l M d Y H:i:s',$r['actualendtime']).'" title="#'.$r['id'].'　'.htmlspecialchars($r['title']).'" isDuration="true"></event>';
		}
		$xml .= '</data>';
		echo $xml;

	}

	private function looptask()
	{
		$looptype1 = array('everyday','weekly','permonth');
		$looptype2 = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		$looptype3 = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
		$sql = "loopstatus = 0 AND looptask != 'once' AND siteid = ".SITEID;
		$result = oa_model_task::model()->select($sql);
		$loop_arr = array();
		foreach($result as $r)
		{
			$loop_arr = $r;
			if(in_array($r['looptask'], $looptype1))
			{
				switch($r['looptask'])
				{
					case 'once':
						break;
					case 'everyday':
						$loop_arr['planstarttime'] = $r['planstarttime']+24*60*60;
						$loop_arr['planendtime'] = $r['planendtime']+24*60*60;
						break;
					case 'weekly':
						$days = round(($r['planendtime']-$r['planstarttime'])/3600/24);
						$loop_arr['planstarttime'] = $startday = strtotime("next Monday", $r['planstarttime']);
						$loop_arr['planendtime'] = strtotime("$days days",$startday);
						break;
					case 'permonth':
						$loop_arr['planstarttime'] = strtotime(date('Y',$r['planendtime']).'-'.(date('m',$r['planendtime'])+1).'-01');
						$loop_arr['planendtime'] = strtotime(date('Y',$r['planendtime']).'-'.(date('m',$r['planendtime'])+2).'-01')-24*60*60;
						break;
				}
			}
			elseif(in_array($r['looptask'], $looptype2))
			{
				$days = round(($r['planendtime']-$r['planstarttime'])/3600/24);
				$week = $r['looptask'];
				$loop_arr['planstarttime'] = $startday = strtotime("next $week", $r['planstarttime']);
				$loop_arr['planendtime'] = strtotime("$days days",$startday);
			}
			elseif(in_array($r['looptask'], $looptype3))
			{
				$days = round(($r['planendtime']-$r['planstarttime'])/3600/24);
				$loop_arr['planstarttime'] = $startday = strtotime(date('Y',$r['planendtime']).'-'.(date('m',$r['planendtime'])+1).'-'.$r['looptask']);
				$loop_arr['planendtime'] = strtotime("$days days",$startday);
			}
			else
			{
				continue;
			}
			if(!empty($loop_arr))
			{
				if($loop_arr['planstarttime'] < time() && $loop_arr['loopstatus'] == 0)
				{

					oa_model_task::model()->update(array('loopstatus'=>1),array('id'=>$r['id']));
					unset($loop_arr['id']);
					$to = array();
					$to['sponsor'] = $loop_arr['sponsor'];
					$to['assignedto'] = $loop_arr['assignedto'];
					$to['title'] = $loop_arr['title'];
					$to['planstarttime'] = $loop_arr['planstarttime'];
					$to['planendtime'] = $loop_arr['planendtime'];
					$to['tagid'] = $loop_arr['tagid'];
					$to['looptask'] = $loop_arr['looptask'];
					$to['loopstatus'] = 0;
					$to['loopfrom'] = $loop_arr['loopfrom'];
					$to['attachment'] = $loop_arr['attachment'];
					$to['intro'] = $loop_arr['intro'];
					$to['status'] = 1;
					$to['siteid'] = SITEID;
					oa_model_task::model()->insert($to);
				}
			}
			unset($loop_arr);
		}
		return true;
	}

	private function get_rated($userid = '' ,$lastmonth = false)
	{
		if(empty($userid))
		{
			return false;
		}
		else
		{
			if($lastmonth)
			{
				$sql = 'assignedto = '.$userid.' AND status = 99 AND actualendtime BETWEEN '.strtotime("-1 month", time()).' AND '.strtotime(date('Y-m'));
			}
			else
			{
				$sql = 'assignedto = '.$userid.' AND status = 99 AND actualendtime >'.strtotime(date('Y-m'));
			}
			$infos = oa_model_task::model()->select($sql);
			$r_arr = array();
			foreach($infos as $info)
			{
				$r_arr[] = $info['compentedrated'];
			}
			$rated = array_sum($r_arr);
			return $rated;	
		}
	}

	private function get_selfrated($userid = '' ,$lastmonth = false)
	{
		if(empty($userid))
		{
			return false;
		}
		else
		{
			if($lastmonth)
			{
				$sql = 'assignedto = '.$userid.' AND status = 99 AND actualendtime BETWEEN '.strtotime("-1 month", time()).' AND '.strtotime(date('Y-m'));
			}
			else
			{
				$sql = 'assignedto = '.$userid.' AND status = 99 AND actualendtime >'.strtotime(date('Y-m'));
			}
			$infos = oa_model_task::model()->select($sql);
			$r_arr = array();
			foreach($infos as $info)
			{
				$r_arr[] = $info['selfrated'];
			}
			$rated = array_sum($r_arr);
			return $rated;	
		}
	}
}
?>