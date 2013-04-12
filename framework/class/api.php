<?php
/**
 *  api.php api 封装了应用接口的基本启动流程和初始化操作，并为应用接口提供一些公共服务。
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-07
 * 
 * 主要完成下列任务：
 * - 初始化运行环境
 * - 提供应用接口
 * - 为应用接口提供公共服务
 */
class api
{
	/**
     * 构造函数
     *
     * @context array $app_config
     *
     * 构造应用程序对象
     */
	protected function __construct()
	{
		// 自动转义数据
		if(!get_magic_quotes_gpc())
		{
			$_POST = new_addslashes($_POST);
			$_GET = new_addslashes($_GET);
			$_REQUEST = new_addslashes($_REQUEST);
			$_COOKIE = new_addslashes($_COOKIE);
		}
	}
	
	/**
     * 返回应用接口类的唯一实例
     *
     *
     * @return application
     */
    static function instance()
    {
        static $instance;
        if (is_null($instance))
        {
            $instance = new api();
        }
        return $instance;
    }
	
	/**
     * 根据运行时上下文对象，调用相应的控制器动作方法
     *
     *
     * @return mixed
     */
    function dispatching()
    {
		// 构造运行时上下文对象
        $context = context::instance();
		// 获得请求对应的 UDI（统一目的地信息）
        $udi = $context->requestUDI('array');
		
    	$filepath = Next::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$udi[context::UDI_MODULE].DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.$udi[context::UDI_ACTION].'.php';
    	if (file_exists($filepath))
    	{
    		include $filepath;
    	}
    	else 
        {
			$this->showmessage('300','['.$filepath.'] does not exist.');
        }
    }
	
	/**
	 * 提示信息处理函数
	 */
	protected function showmessage($statusCode = '300',$msg = '')
    {
        echo $msg;
	}
}
?>
