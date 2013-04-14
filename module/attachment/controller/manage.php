<?php
class attachment_controller_manage extends admin_class_controller
{
	function action_init()
	{
		if(!$_SESSION['email']) return false;
		$dir = isset($_GET['dir']) && trim($_GET['dir']) ? str_replace(array('..\\', '.\\', '../', './'), '', trim($_GET['dir'])) : '';
		$sitelist = getcache('sitelist','admin');
		$filepath = Next::config('system','site_root',APP_PATH.'siteroot'.DIRECTORY_SEPARATOR).$sitelist[SITEID]['dirname'].DIRECTORY_SEPARATOR.'uploadfile'.DIRECTORY_SEPARATOR.$dir;
		$list = glob($filepath.DIRECTORY_SEPARATOR.'*');
		if(!empty($list)) rsort($list);
		$url = $sitelist[SITEID]['url'].'uploadfile/'.$dir.'/';
		$local = str_replace(array(Next::config('system','site_root',APP_PATH.'siteroot'.DIRECTORY_SEPARATOR).$sitelist[SITEID]['dirname'].DIRECTORY_SEPARATOR,Next_PATH, APP_PATH ,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR), array('','','',DIRECTORY_SEPARATOR), $filepath);
		include $this->view('attachment','manage','init');
	}
}
?>