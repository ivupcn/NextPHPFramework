<?php
class admin_controller_module extends admin_class_controller
{
	function action_init()
	{
		$dirs = $module = $dirs_arr = $directory = array();
		$dirs = glob(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).'*');
		foreach ($dirs as $d)
		{
			if (is_dir($d))
			{
				$d = basename($d);
				$dirs_arr[] = $d;
			}
		}
		define('INSTALL', true);
		$modules =admin_model_module::model()->select('', '*', '', '', '', 'module');
		$total = count($dirs_arr);
		$dirs_arr = array_chunk($dirs_arr, 20, true);
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$pages = pages($total, $page, 20);
		$directory = $dirs_arr[intval($page-1)];
		include $this->view('admin','module','init');
	}

	function action_install()
	{
		$module = isset($_POST['module']) ? $_POST['module'] : $_GET['module'];
		$module_api = new admin_class_module();
		if (!$module_api->check($module)) $this->_app->showmessage('300',$module_api->error_msg,url('module::init@admin'),'closeCurrent','admin_module_init');
		if ($this->_context->isPOST())
		{
			if($module_api->install($module))
			{
				$this->_app->showmessage('200','模块安装成功！',url('module::init@admin'),'closeCurrent','admin_module_init');
			}
			else
			{
				$this->_app->showmessage('300',$module_api->error_msg,url('module::init@admin'),'closeCurrent','admin_module_init');
			}
		}
		else
		{
			include Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$module.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'config.php';
			include $this->view('admin','module','config');
		}
	}

	function uninstall()
	{
		if(!isset($_GET['module']) || empty($_GET['module'])) showmessage('300','非法参数！');
		
		$module_api = new admin_class_module();
		if(!$module_api->uninstall($_GET['module']))
		{
			$this->_app->showmessage('300',$module_api->error_msg,url('module::init@admin'),'closeCurrent','admin_module_init');
		}
		else
		{
			$this->_app->showmessage('200','模块卸载成功！');
		}
	}
}
?>