<?php
/**
 *  controller.php controller 应用程序的公共控制器基础类
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-06
 */

abstract class controller
{
	/**
     * 封装请求的对象
     *
     * @var QContext
     */
    protected $_context;
	
	/**
     * 构造函数
     */
    public function __construct()
    {
        $this->_context = context::instance();
    }
	
	/**
     * 调用相应的视图方法
     */
	protected function view($module = context::UDI_DEFAULT_MODULE, $controller = context::UDI_DEFAULT_CONTROLLER, $view = context::UDI_DEFAULT_ACTION, $siteid = null)
	{
		$module = str_replace('/', DIRECTORY_SEPARATOR, $module);
        if($siteid)
        {
            $site_root = Next::config('system','site_root',APP_PATH.'siteroot'.DIRECTORY_SEPARATOR);
            $sitelist = getcache('sitelist','admin');
            $site_dirname = $sitelist[$siteid]['dirname'];
            $style = $sitelist[$siteid]['view'];
            $originalviewfile = $site_root.$site_dirname.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.$view.'.php';
            if(!file_exists($originalviewfile))
            {
                $originalviewfile = Next::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.$view.'.php';
            }
            $compiledviewpath = Next::config('system', 'cache_path', APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_view'.DIRECTORY_SEPARATOR.$site_dirname.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR;
            $compiledviewfile = $view.'.php';
        }
        else 
        {
            $originalviewfile = Next::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.$view.'.php';
            $compiledviewpath = Next::config('system', 'cache_path', APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_view'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR;
            $compiledviewfile = $view.'.php';
        }
        $xview = new NView();
        if(!file_exists($compiledviewpath.$compiledviewfile) || (file_exists($originalviewfile) && filemtime($originalviewfile) > filemtime($compiledviewpath.$compiledviewfile))) 
        {
            $xview->view_compile($originalviewfile, $compiledviewpath, $compiledviewfile);
        }
        return $compiledviewpath.$compiledviewfile;
	}
}
?>
