<?php
class content_controller_category extends admin_class_controller
{
	public function action_init()
	{
		$tree = new tree();
		$models = getcache('model_'.SITEID,'model');
		$sitelist = getcache('sitelist','admin');
		$category_items = array();
		foreach ($models as $modelid => $model)
		{
			$category_items[$modelid] = getcache('category_items_'.$modelid,'content');
		}
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
		$categorys = array();
		//读取缓存
		$result = getcache('category_content_'.SITEID,'content');
		$show_detail = count($result) < 500 ? 1 : 0;
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$site_root = Next::config('system','site_root',APP_PATH.'siteroot'.DIRECTORY_SEPARATOR);
		$types = array(0 => '内部栏目',1 => '<font color="blue">单网页</font>',2 => '<font color="red">外部链接</font>');
		if(!empty($result))
		{
			foreach($result as $r)
			{
				$r['str_manage'] = '';
				if(!$show_detail)
				{
					if($r['parentid']!=$parentid) continue;
					$r['parentid'] = 0;
					$r['str_manage'] .= '<a href="'.$this->_context->url('category::init@content','catid/'.$r['catid'].'/type/'.$r['type']).'">管理子栏目</a> | ';
				}
				$r['str_manage'] .= '<a href="'.$this->_context->url('category::add@content','catid/'.$r['catid'].'/type/'.$r['type']).'">添加子栏目</a> | ';
				$r['str_manage'] .= '<a href="'.$this->_context->url('category::edit@content','catid/'.$r['catid']).'" target="dialog" mask="true" maxable="false" rel="content_category_edit" width="800" height="560">修改</a> | <a href="'.$this->_context->url('category::delete@content','catid/'.$r['catid']).'">删除</a> | <a href="'.$this->_context->url('category::remove@content','catid/'.$r['catid']).'">移动</a>';
				$r['typename'] = $types[$r['type']];
				$r['display_icon'] = $r['ismenu'] ? '' : '（不在导航显示）';
				if($r['type'] || $r['child'])
				{
					$r['items'] = '';
				}
				$setting = unserialize($r['setting']);
				if($r['url'])
				{
					if(preg_match('/^(http|https):\/\//', $r['url']))
					{
						$catdir = $site_root.'html'.DIRECTORY_SEPARATOR.$r['catdir'];
					}
					else
					{
						$r['url'] = substr($sitelist[SITEID]['domain'],0,-1).$r['url'];
					}
					$r['url'] = "<a href='$r[url]' target='_blank'>访问</a>";
				}
				else
				{
					$r['url'] = '<a href="'.$this->_context->url('category::cache@content').'"><font color="red">更新缓存</font></a>';
				}
				$categorys[$r['catid']] = $r;
			}
		}
		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$catname\$display_icon</td>
					<td align='center'>\$typename</td>
					<td align='center'>\$items</td>
					<td align='center'>\$url</td>
					<td align='center' >\$str_manage</td>
				</tr>";
		$tree->init($categorys);
		$categorys = $tree->get_tree(0, $str);
		$this->_cache();
		include $this->view('content','category','init');
	}

	public function action_add()
	{
		if($this->_context->isPOST() &&  content_model_category::model()->validate($_POST['info']))
		{
			$info = $_POST['info'];
			if($info['type']!=2)
			{
				if($info['catdir']=='') $this->_app->showmessage('300','请输入目录名称！');
				if(!$this->_checkcatdir($info['catdir'],$info['parentid'])) $this->_app->showmessage('300','目录名称已存在！');
			}
			$info['siteid'] = SITEID;
			$info['module'] = 'content';
			$setting = $_POST['setting'];
			$setting['model'] = $_POST['model'];
			$info['setting'] = serialize($setting);
			$insert_id = content_model_category::model()->FIELDVALUE($info)->insert();
			if($insert_id)
			{
				$this->_app->showmessage('200','操作成功！',$this->_context->url('category::init@content'),'closeCurrent','content_category_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
			if(isset($_GET['parentid']))
			{
				$parentid = $_GET['parentid'];
				$r = content_model_category::model()->WHERE(array('catid'=>$parentid))->select(1);
				if($r) extract($r,EXTR_SKIP);
				$setting = unserialize($setting);
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
		if($this->_context->isPOST() &&  content_model_category::model()->validate($_POST['info']))
		{
			$catid = isset($_POST['catid']) && intval($_POST['catid']) ? intval($_POST['catid']) : $this->_app->showmessage('300','操作失败！');
			$info = $_POST['info'];
			if($info['type']!=2)
			{
				if($info['catdir']=='') $this->_app->showmessage('300','请输入目录名称！');
				if(!$this->_checkcatdir($info['catdir'],$info['parentid'],$_POST['oldcatdir'],$catid)) $this->_app->showmessage('300','目录名称已存在！');
			}
			$setting = $_POST['setting'];
			$setting['model'] = $_POST['model'];
			$info['setting'] = serialize($setting);
			content_model_category::model()->SET($info)->WHERE(array('catid'=>$catid))->update();
			$this->_app->showmessage('200','操作成功！',$this->_context->url('category::init@content'),'closeCurrent','content_category_init');
		}
		else
		{
			$catid = isset($_GET['catid']) && intval($_GET['catid']) ? intval($_GET['catid']) : $this->_app->showmessage('300','操作失败！');
			$info = content_model_category::model()->WHERE(array('catid'=>$catid))->select(1);
			$setting = unserialize($info['setting']);
			$models = getcache('model_'.SITEID,'model');
			$workflows = getcache('workflow_'.SITEID,'extend');
			if($workflows)
			{
				$workflows_datas = array();
				foreach($workflows as $_k=>$_v)
				{
					$workflows_datas[$_v['workflowid']] = $_v['workname'];
				}
				$workflow =  form::select($workflows_datas,$setting['workflowid'],'name="setting[workflowid]"','不需要审核');
			}
			else
			{
				$workflow = '<input type="hidden" name="setting[workflowid]" value="" />';
			}
			include $this->view('content','category','edit');
		}
	}

	public function action_delete()
	{
		
	}

	public function action_listorder()
	{
		
	}

	private function _cache()
	{
		$categorys = $array = array();
		$categorys = content_model_category::model()->FIELD('catid,siteid')->WHERE(array('module'=>'content'))->ORDER('listorder ASC')->select();
		foreach($categorys as $r)
		{
			$array[$r['catid']] = $r['siteid'];
		}
		setcache('category_content',$array,'content');
		$categorys = $cats = array();
		$cats = content_model_category::model()->WHERE(array('siteid'=>SITEID, 'module'=>'content'))->ORDER('listorder ASC')->select();
		foreach($cats as $r)
		{
			unset($r['module']);
			$setting = unserialize($r['setting']);
			$r['ishtml'] = $setting['ishtml'];
			$r['content_ishtml'] = $setting['content_ishtml'];
			$r['workflowid'] = $setting['workflowid'];
			if(!preg_match('/^(http|https):\/\//', $r['url']))
			{
				$sitelist = getcache('sitelist','admin');
				$r['url'] = substr($sitelist[$r['siteid']]['domain'],0,-1).$r['url'];
			}
			$categorys[$r['catid']] = $r;
		}
		setcache('category_content_'.SITEID,$categorys,'content');
		return true;
	}

	private function _checkcatdir($catdir = '', $parentid = 0, $oldcatdir = '', $catid = 0)
	{
		$r = content_model_category::model()->WHERE(array('siteid'=>SITEID,'module'=>'content','catdir'=>$catdir,'parentid'=>$parentid))->select(1);
		if($r && $r['catid'] == $catid)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
?>