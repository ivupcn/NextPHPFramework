<?php
class admin_controller_site extends admin_class_controller
{
	function action_init()
	{
		$list = admin_model_site::model()->select();
		include $this->view('admin','site','init');
	}

	function action_add()
	{
		if($this->_context->isPOST() && admin_model_site::model()->validate($_POST['info']))
		{
			$infos = array();
			foreach($_POST['info'] as $k => $v)
			{
				if($k == 'setting')
				{
					$infos['setting'] = trim(array2string($_POST['info'][$k]));
				}
				elseif($k == 'route')
				{
					$infos['route'] = trim(array2string($_POST['info'][$k]));
				}
				else
				{
					$infos[$k] = trim($v);
				}
			}
			if(admin_model_site::model()->FIELD('siteid')->WHERE(array('name'=>$infos['name']))->select(1)) {
				showmessage('300','该站点已经存在');
			}
			if(admin_model_site::model()->FIELDVALUE($infos)->insert())
			{
				$class_site = new admin_class_sites();
				$class_site->set_cache();
				$this->_app->showmessage('200','操作成功！',$this->_context->url('site::init@admin'),'closeCurrent','admin_site_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			include $this->view('admin','site','add');
		}
	}

	function action_edit()
	{
		if($this->_context->isPOST() && admin_model_site::model()->validate($_POST['info']))
		{
			$siteid = isset($_POST['siteid']) && intval($_POST['siteid']) ? intval($_POST['siteid']) : $this->_app->showmessage('300','非法参数！');
			$info = array();
			foreach($_POST['info'] as $k => $v)
			{
				if($k == 'setting')
				{
					$info['setting'] = trim(array2string($_POST['info'][$k]));
				}
				elseif($k == 'route')
				{
					$info['route'] = trim(array2string($_POST['info'][$k]));
				}
				else
				{
					$info[$k] = trim($v);
				}
			}
			if($data['name'] != $info['name'] && admin_model_site::model()->FIELD('siteid')->WHERE(array('name'=>$info['name']))->select(1))
			{
				$this->_app->showmessage('300','站点名称已经存在');
			}
			if($siteid != 1)
			{
				if($data['dirname'] != $info['dirname'] && admin_model_site::model()->FIELD('siteid')->WHERE(array('dirname'=>$info['dirname']))->select(1))
				{
					$this->_app->showmessage('300','站点目录已经存在');
				}
			}
			if($siteid == 1) unset($info['dirname']);
			if(admin_model_site::model()->SET($info)->WHERE(array('siteid'=>$siteid)))
			{
				$class_site = new admin_class_sites();
				$class_site->set_cache();
				$this->_app->showmessage('200','操作成功！',$this->_context->url('site::edit@admin',array('siteid'=>$siteid)),'','','jbsxBox');
			}
			else
			{
				$this->_app->showmessage('300','操作失败！');
			}
		}
		else
		{
			$siteid = isset($_GET['siteid']) && intval($_GET['siteid']) ? intval($_GET['siteid']) : $this->_app->showmessage('300','非法参数！');
			$data = admin_model_site::model()->WHERE(array('siteid'=>$siteid))->select(1);
			$view_list = $this->viewlist($siteid);
			$setting = string2array($data['setting']);
			$route = string2array($data['route']);
			$swf_auth_key = md5(Next::config('system','auth_key','29HTvKg84Veg8VtDdKbs').SYS_TIME);
			include $this->view('admin','site','edit');
		}
	}

	function action_config()
	{
		if($data = admin_model_site::model()->WHERE(array('siteid'=>SITEID))->select(1))
		{
			if($this->_context->isPOST())
			{
				$info = array();
				foreach($_POST['info'] as $k => $v)
				{
					if($k == 'setting')
					{
						$info['setting'] = trim(array2string($_POST['info'][$k]));
					}
					else
					{
						$info[$k] = trim($v);
					}
				}
				if(admin_model_site::model()->SET($info)->WHERE(array('siteid'=>$data['siteid']))->update())
				{
					$class_site = new admin_class_sites();
					$class_site->set_cache();
					$this->_app->showmessage('200','操作成功！',$this->_context->url('site::config@admin'),'','admin_site_config');
				}
				else
				{
					$this->_app->showmessage('300','操作失败！');
				}
			}
			else
			{
				$view_list = $this->viewlist(SITEID);
				$setting = string2array($data['setting']);
				include $this->view('admin','site','config');
			}
		}
		else
		{
			$this->_app->showmessage('300','操作失败！');
		}
	}

	function action_delete()
	{
		$siteid = isset($_GET['s']) && intval($_GET['s']) ? intval($_GET['s']) : $this->_app->showmessage('300','非法参数！');
		if (admin_model_site::model()->WHERE(array('siteid'=>$siteid))->select(1))
		{
			if(admin_model_site::model()->WHERE(array('siteid'=>$siteid))->delete())
			{
				$class_site = new admin_class_sites();
				$class_site->set_cache();
				$this->_app->showmessage('200','操作成功！',$this->_context->url('site::init@admin'),'','admin_site_init');
			}
			else
			{
				$this->_app->showmessage('300','操作失败');
			}
		}
		else
		{
			$this->_app->showmessage('300','操作失败');
		}
	}

	private function check_gd()
	{
		if(!function_exists('imagepng') && !function_exists('imagejpeg') && !function_exists('imagegif'))
		{
			$gd = '<font color="red">不支持</font>';
		}
		else
		{
			$gd = '<font color="green">支持</font>';
		}
		return $gd;
	}

	/**
     * 模板风格列表
     * @param integer $siteid 站点ID，获取单个站点可使用的模板风格列表
     * @param integer $disable 是否显示停用的{1:是,0:否}
     */
    private function viewlist($siteid, $disable = 0)
    {
        if($siteid == '') $this->_app->showmessage('300','参数错误');
        $site = new admin_class_sites();
        $siteinfo = $site->get_siteinfo($siteid);
        $site_root = Next::config('system','site_root',APP_PATH.'siteroot');
        $list = glob($site_root.DIRECTORY_SEPARATOR.$siteinfo['dirname'].DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
        $arr = $template = array();
        if($list)
        {
            foreach ($list as $key=>$v)
            {
                $dirname = basename($v);
                $arr[$key]=$dirname;
            }
        }
        return $arr;
    }
}
?>