<?php
class content_controller_category extends admin_class_controller
{
	public function action_init()
	{
		include $this->view('content','category','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() &&  content_model_model::model()->validate($_POST['info']))
		{

		}
		else
		{
			if(isset($_GET['parentid']))
			{
				$parentid = $_GET['parentid'];
				$r = content_model_category::model()->WHERE(array('catid'=>$parentid))->select(1);
				if($r) extract($r,EXTR_SKIP);
				$setting = string2array($setting);
			}
			else
			{
				$parentid = 0;
			}
			$models = getcache('model_'.SITEID,'model');
			$workflows = getcache('workflow_'.SITEID,'extend');
			if($workflows)
			{
				$workflows_datas = array();
				foreach($workflows as $_k=>$_v)
				{
					$workflows_datas[$_v['workflowid']] = $_v['workname'];
				}
				$workflow =  form::select($workflows_datas,'','name="setting[workflowid]"','不需要审核');
			}
			else
			{
				$workflow = '<input type="hidden" name="setting[workflowid]" value="" />';
			}
			include $this->view('content','category','add');
		}
	}

	public function action_edit()
	{
		include $this->view('content','category','edit');
	}

	public function action_delete()
	{
		
	}

	public function action_listorder()
	{
		
	}
}
?>