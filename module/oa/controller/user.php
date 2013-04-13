<?php
class oa_controller_user extends user_class_controller
{
	public function action_init()
	{
		$callback = null;
		$userid = $_SESSION['userid'];
		include $this->view('oa','user','init',ROUTE_S);
	}

	public function action_task()
	{
		$callback = $this->_callback($_GET['callback']);
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$groupid = $_SESSION['groupid'];
		$userid = $_SESSION['userid'];
		switch ($callback)
		{
			case 'all':
				include $this->view('oa','user','taskall',ROUTE_S);
				break;
			case 'assignedto':
				include $this->view('oa','user','taskassignedto',ROUTE_S);
				break;
			case 'sponsor':
				include $this->view('oa','user','tasksponsor',ROUTE_S);
				break;
			case 'acceptance':
				include $this->view('oa','user','taskacceptance',ROUTE_S);
				break;
			case 'assessment':
				include $this->view('oa','user','taskassessment',ROUTE_S);
				break;
			case 'publish':
				if($this->_context->isPOST() && oa_model_task::model()->validate($_POST['info']))
				{

				}
				else
				{
					include $this->view('oa','user','taskpublish',ROUTE_S);
				}
				break;
			case 'show':
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','参数错误');
				$taskinfo = oa_model_task::model()->get_one(array('id'=>$id));
				include $this->view('oa','user','taskshow',ROUTE_S);
				break;
			default:
				include $this->view('oa','user','taskall',ROUTE_S);
				break;
		}
	}

	private function _callback($callback)
	{
		if(isset($callback))
		{
			preg_match('/^[a-zA-Z_][a-zA-Z0-9_]+$/', $_GET['callback'],$matches);
			if(isset($matches[0]))
			{
				return $res = $matches[0];
			}
		}
		else
		{
			return false;
		}
	}
}
?>