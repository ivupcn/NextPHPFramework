<?php
class admin_controller_menu extends admin_class_controller
{
	function action_init()
	{
		$tree = new tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$userid = $_SESSION['userid'];
		$admin_email = $this->_context->get_cookie('admin_email');
	
		$result = admin_model_menu::model()->ORDER('listorder ASC,id DESC')->select();
		$array = array();
		foreach($result as $r)
		{
			$r['str_manage'] = '<a href="'.$this->_context->url('menu::add@admin','parentid/'.$r['id']).'" target="dialog" mask="true" maxable="false" rel="admin_menu_add" width="500" height="400">添加子菜单</a> | <a href="'.$this->_context->url('menu::edit@admin','id/'.$r['id']).'" target="dialog" mask="true" maxable="false" rel="admin_menu_edit" width="500" height="400">修改</a> | <a href="'.$this->_context->url('menu::delete@admin','id/'.$r['id']).'" target="ajaxTodo" title="确定要删除吗?">删除</a> ';
			$r['sys'] = $r['sys'] ? '<img src="'.Next::config('system','admin_url').'statics/images/icon/gear_disable.png" width="16" height="16" title="系统菜单" />' : null;
			$array[] = $r;
		}

		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' value='\$listorder' class='input-text-c' style='width:30px'></td>
					<td align='center'>\$id</td>
					<td>\$spacer\$name\$sys</td>
					<td>\$m</td>
					<td>\$c</td>
					<td>\$a</td>
					<td>\$data</td>
					<td>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		$show_dialog = true;
		include $this->view('admin','menu','init');
	}

	function action_add()
	{
		if($this->_context->isPOST() &&  admin_model_menu::model()->validate($_POST['info']))
		{
			admin_model_menu::model()->FIELDVALUE($_POST['info'])->insert();
			$this->_app->showmessage('200','操作成功！',$this->_context->url('menu::init@admin'),'closeCurrent','admin_menu_init');
		}
		else
		{
			$show_validator = $show_header = true;
			$tree = new tree();
			$parentid = isset($_GET['parentid'])? intval($_GET['parentid']) : 0;
			$result = admin_model_menu::model()->select();
			$array = array();
			foreach($result as $r)
			{
				$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
			
			include $this->view('admin','menu','add');
		}
	}

	function action_edit()
	{
		if($this->_context->isPOST() &&  admin_model_menu::model()->validate($_POST['info']))
		{
			$id = intval($_POST['id']);
			admin_model_menu::model()->SET($_POST['info'])->WHERE(array('id'=>$id))->update();
			$this->_app->showmessage('200','操作成功！',$this->_context->url('menu::init@admin'),'closeCurrent','admin_menu_init');
		}
		else
		{
			$show_validator = $show_header = true;
			$array = $r = '';
			$tree = new tree();
			$id = intval($_GET['id']);
			$r = admin_model_menu::model()->WHERE(array('id'=>$id))->select(1);
			if($r) extract($r);
			$result = admin_model_menu::model()->select();
			foreach($result as $r)
			{
				$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
			include $this->view('admin','menu','edit');
		}
	}

	function action_delete()
	{
		$_GET['id'] = intval($_GET['id']);
		admin_model_menu::model()->WHERE(array('id'=>$_GET['id']))->delete();
		$this->_app->showmessage('200','操作成功！',$this->_context->url('menu::init@admin'),'','admin_menu_init');
	}

	function action_listorder()
	{
		if($this->_context->isPOST())
		{
			foreach($_POST['listorders'] as $id => $listorder)
			{
				admin_model_menu::model()->SET(array('listorder'=>$listorder))->WHERE(array('id'=>$id))->update();
			}
			$this->_app->showmessage('200','操作成功！',$this->_context->url('menu::init@admin'));
		}
		else
		{
			$this->_app->showmessage('300','操作失败！');
		}
	}
}
?>