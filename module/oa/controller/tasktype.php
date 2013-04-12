<?php
class oa_controller_tasktype extends admin_class_controller
{
	public function action_init()
	{
		$workflow = getcache('workflow_'.$this->get_siteid(),'extend');
		$tree = new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
	
		$result = admin_model_tag::model()->select(array('siteid'=>$this->get_siteid(),'module'=>'oa','type'=>'task_category'),'*','','listorder ASC,id DESC');
		$array = array();
		foreach($result as $r)
		{
			$r['str_manage'] = '<a href="?m=oa&c=tasktype&a=add&parentid='.$r['id'].'" target="dialog" mask="true" maxable="false" rel="oa_tasktype_add" width="800" height="400">添加子类</a> | <a href="?m=oa&c=tasktype&a=edit&id='.$r['id'].'" target="dialog" mask="true" maxable="false" rel="oa_tasktype_edit" width="800" height="400">修改</a> | <a href="?m=oa&c=tasktype&a=delete&id='.$r['id'].'" target="ajaxTodo" title="确定要删除吗?">删除</a> ';
			$setting = string2array($r['setting']);
			$r['workflowid'] = $setting['workflowid'] ? $workflow[$setting['workflowid']]['workname'] : '无';
			$r['presentpoint'] = $setting['presentpoint'];
			$array[] = $r;
		}

		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' value='\$listorder' class='input-text-c' style='width:30px'></td>
					<td align='center'>\$id</td>
					<td>\$spacer\$tagname</td>
					<td align='center'>\$workflowid</td>
					<td align='center'>\$presentpoint</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$tags = $tree->get_tree(0, $str);
		$category = new admin_class_category();
		$category->repair($this->get_siteid(),'oa');
		$category->cache($this->get_siteid(),'oa');
		include $this->view('oa','tasktype','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() &&  admin_model_tag::model()->validate($_POST['info']))
		{
			$info = $_POST['info'];
			$info['type'] = 'task_category';
			$info['siteid'] = $this->get_siteid();
			$info['module'] = 'oa';
			$info['description'] = addslashes($info['description']);
			$info['parentid'] = intval($info['parentid']);
			$setting = array();
			$setting['workflowid'] = isset($_POST['setting']['workflowid']) && intval($_POST['setting']['workflowid']) ? intval($_POST['setting']['workflowid']) : 0;
			$setting['presentpoint'] = isset($_POST['setting']['presentpoint']) && floatval($_POST['setting']['presentpoint']) ? floatval($_POST['setting']['presentpoint']) : 0;
			$info['setting'] = array2string($setting);
			if(intval($_POST['addtype']) == 1)
			{
				if($_POST['batch_add']=='') $this->_app->showmessage('300','请输入类型名称');
				$batch_adds = explode("\n", $_POST['batch_add']);
				foreach ($batch_adds as $_v)
				{
					if(trim($_v)=='') continue;
					$names = explode('|', $_v);
					$catname = $names[0];
					$info['tagname'] = trim($names[0]);
					$insert_id = admin_model_tag::model()->insert($info, true);
				}
			}
			else
			{
				if($info['tagname']=='') $this->_app->showmessage('300','请输入类型名称');
				$info['tagname'] = safe_replace($info['tagname']);
				$insert_id = admin_model_tag::model()->insert($info, true);
			}
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('tasktype::init@oa'),'closeCurrent','oa_tasktype_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$parentid = isset($_GET['parentid']) && intval($_GET['parentid']) ? intval($_GET['parentid']) : 0;
			include $this->view('oa','tasktype','add');
		}
	}

	public function action_edit()
	{
		if($this->_context->isPOST() &&  admin_model_tag::model()->validate($_POST['info']))
		{
			$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->_app->showmessage('300','操作失败！');
			$info = $_POST['info'];
			$info['description'] = addslashes($info['description']);
			$info['parentid'] = intval($info['parentid']);
			$setting = array();
			$setting['workflowid'] = isset($_POST['setting']['workflowid']) && intval($_POST['setting']['workflowid']) ? intval($_POST['setting']['workflowid']) : 0;
			$setting['presentpoint'] = isset($_POST['setting']['presentpoint']) && floatval($_POST['setting']['presentpoint']) ? floatval($_POST['setting']['presentpoint']) : 0;
			$info['setting'] = array2string($setting);
			if($info['tagname']=='') $this->_app->showmessage('300','请输入类型名称');
			$info['tagname'] = safe_replace($info['tagname']);
			admin_model_tag::model()->update($info, array('id'=>$id));
			$this->_app->showmessage('200','操作成功！',$this->_context->url('tasktype::init@oa'),'closeCurrent','oa_tasktype_init');
		}
		else
		{
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
			$info = admin_model_tag::model()->get_one(array('id'=>$id));
			$setting = string2array($info['setting']);
			include $this->view('oa','tasktype','edit');
		}
	}

	public function action_delete()
	{
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->_app->showmessage('300','操作失败！');
		$categorys = getcache('category_oa_'.$this->get_siteid(),'admin');
		$category = new admin_class_category();
		$category->delete_child($id);
		admin_model_tag::model()->delete(array('id'=>$id));
		$this->_app->showmessage('200','操作成功！',$this->_context->url('tasktype::init@oa'),'','oa_tasktype_init');
	}

	public function action_listorder()
	{
		if($this->_context->isPOST())
		{
			foreach($_POST['listorders'] as $id => $listorder)
			{
				admin_model_tag::model()->update(array('listorder'=>$listorder),array('id'=>$id));
			}
			$this->_app->showmessage('200','操作成功！',$this->_context->url('tasktype::init@oa'),'','oa_tasktype_init');
		}
		else
		{
			$this->_app->showmessage('300','操作失败！');
		}
	}

}
?>