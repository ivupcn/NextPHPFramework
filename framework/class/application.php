<?php
/**
 *  application.php application 封装了应用程序的基本启动流程和初始化操作，并为应用程序提供一些公共服务。
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-01
 * 
 * 主要完成下列任务：
 * - 初始化运行环境
 * - 提供应用程序入口
 * - 为应用程序提供公共服务
 */
class application
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
		NLOG::log('--- STARTUP TIME --- ' . SYS_START_TIME);

        Next::loadClass('session'.Next::config('system','session_storage','File'),'1');
        NLOG::log('    SESSION_ID：' . session_id());

		// 自动转义数据
		if(!get_magic_quotes_gpc())
		{
			$_POST = new_addslashes($_POST);
			$_GET = new_addslashes($_GET);
			$_REQUEST = new_addslashes($_REQUEST);
			$_COOKIE = new_addslashes($_COOKIE);
		}

        define('ROUTE_M',context::instance()->module_name);
        define('ROUTE_C',context::instance()->controller_name);
        define('ROUTE_A',context::instance()->action_name);

        NLOG::log('    REQUEST MCA：' .ROUTE_C.'::'.ROUTE_A.'@'.ROUTE_M);
	}
	
	/**
     * 析构函数
     */
	public function __destruct()
	{
		$shutdown_time = microtime(true);
		$timeLength = $shutdown_time - SYS_START_TIME;
		NLOG::log('--- SHUTDOWN TIME --- '.$shutdown_time.' ('.$timeLength.')sec');
	}
	
	/**
     * 返回应用程序类的唯一实例
     *
     *
     * @return application
     */
    static function instance(array $app_config = null)
    {
        static $instance;
        if (is_null($instance))
        {
            $instance = new application($app_config);
        }
        return $instance;
    }
	
	/**
     * 根据运行时上下文对象，调用相应的控制器动作方法
     *
     * @context array $args
     *
     * @return mixed
     */
    public function dispatching()
    {
		// 获得用户角色
		$roles = isset($_SESSION['roleid']) ? $_SESSION['roleid'] : array();
		// 检查是否有权限访问
        if(!$this->authorizedUDI($roles))
        {
            NLOG::log('    ACL：拒绝访问' .ROUTE_C.'::'.ROUTE_A.'@'.ROUTE_M);
            // 拒绝访问
           $this->showmessage('300','No permission resources.');
        }
		else
		{
            // 控制器类名称 = 模块名_controller_控制器名
			$controllerClassName = ROUTE_M.'_controller_'.ROUTE_C;
			Next::loadClass($controllerClassName);
			// 构造控制器对象
			$controller = new $controllerClassName($this);
			if(method_exists($controller, 'action_'.ROUTE_A))
			{
				call_user_func_array(array($controller, 'action_'.ROUTE_A), $this->getUrlParametersByPosition());
			}
			else
			{
                NLOG::error('    [action_'.ROUTE_A.']Action does not exist.');
			}
		}
    }
	
    /**
    * 获取 URL 参数
    *
    * @return array
    */
	public function getUrlParametersByPosition()
	{
		parse_str($_SERVER["QUERY_STRING"],$params);
		if(isset($params[context::UDI_MODULE]))
		{
			unset($params[context::UDI_MODULE]);
		}
		if(isset($params[context::UDI_CONTROLLER]))
		{
			unset($params[context::UDI_CONTROLLER]);
		}
		if(isset($params[context::UDI_ACTION]))
		{
			unset($params[context::UDI_ACTION]);
		}
		return $params;
	}
	
	/**
     * 检查指定角色是否有权限访问特定的控制器和动作
     *
     * @param array $roles
     *
     * @return boolean
     */
    public function authorizedUDI($roles)
    {
        // 如果为超级管理员角色，则跳过ACL检查
        if($roles == 1) return true;
 
        // 通过 ACL 组件进行权限检查
        $roles = normalize($roles);
        $controller_acl = array_change_key_case($this->controllerACL(),CASE_LOWER);
        // 首先检查动作 ACT
        $action_name = strtolower(ROUTE_A);
        if(isset($controller_acl[$action_name]))
        {
            // 如果动作的 ACT 检验通过，则忽略控制器的 ACT
            return NACL::instance()->rolesBasedCheck($roles, $controller_acl[$action_name]);
        }
        elseif(isset($controller_acl[NACL::ALL_ACTIONS]))
        {
            // 如果为所有动作指定了默认 ACT，则使用该 ACT 进行检查
            return NACL::instance()->rolesBasedCheck($roles, $controller_acl[NACL::ALL_ACTIONS]);
        }
    }
	
	/**
     * 获得指定控制器的 ACL
     *
     * @return array
     */
	public function controllerACL()
    {
        $siteid = isset($_SESSION['siteid']) ? $_SESSION['siteid'] : null;
        if($siteid)
        {
            $acl = getcache('acl_'.$siteid,'user');   
        }
        else
        {
            $acl = Next::config('acl');
        }
        $acl = array_change_key_case($acl, CASE_LOWER);
        if (isset($acl[ROUTE_M][ROUTE_C]))
        {
            return (array)$acl[ROUTE_M][ROUTE_C];
        }
        elseif(isset($acl[ROUTE_M][NACL::ALL_CONTROLLERS]))
        {
            return (array)$acl[ROUTE_M][NACL::ALL_CONTROLLERS];
        }
        elseif(isset($acl[NACL::ACL_LOCALHOST]))
        {
            return (array)$acl[NACL::ACL_LOCALHOST];
        }
        else
        {
            NLOG::warn('    ACL-'.$siteid.'-'.ROUTE_M.'-'.ROUTE_C.' item does not in configuration file');
        }
    }

    /**
    * 提示信息页面跳转
    * showmessage('200','登录成功', 'http://www.domain.cn','forward');
    * @param int $statusCode 状态码
    * @param string $message 提示信息
    * @param string $url_forward 跳转地址
    * @param string $callbackType 回调函数
    */
    public function showmessage($statusCode, $message, $url_forward='', $callbackType = 'forward', $navTabId ='', $rel = '') 
    {
        if(defined('IN_ADMIN'))
        {
            $showmessage = array('statusCode'=>$statusCode,'message'=>$message,'callbackType'=>$callbackType,'forwardUrl'=>$url_forward,'navTabId'=>$navTabId,'rel'=>$rel);
            header('Content-Type: application/json');
            exit(json_encode($showmessage));
        }
        else
        {
            include Next_PATH.'data'.DIRECTORY_SEPARATOR.'page'.DIRECTORY_SEPARATOR.'showmessage.php';
            exit;
        }
    }
}
?>
