<?php
class sessionFile {
    public function __construct()
	{
		$path = Next::config('system', 'cache_path', APP_PATH.'cache'.DIRECTORY_SEPARATOR).'session'.DIRECTORY_SEPARATOR;
		if (!is_dir($path))
        {
			mkdir($path, 0777, true);
        }
		ini_set('session.save_handler', 'files');
		session_save_path($path);
		session_start();
    }
}
?>
