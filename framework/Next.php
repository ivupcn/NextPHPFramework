<?php
/**
 *  Next.php NextPHP 框架入口文件
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-01
 */

define('IN_Next', true);
// APP路径
defined('APP_PATH') or exit('Undefined application path');
// NextPHP框架路径
define('Next_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
// 系统开始时间
define('SYS_START_TIME', microtime(true));
// 定义系统时间
define('SYS_TIME', time());
// 设置默认的时区
date_default_timezone_set('Etc/GMT-8');
// 设置自定义的错误处理函数
set_error_handler(array('Next','my_error_handler'));
// gzip输出
ob_start('ob_gzhandler');
// 加载公用函数库
Next::autoloadFunc();
// 注册一个自动类载入方法
Next::registerAutoloader();
//输出页面字符集
header('Content-type: text/html; charset=utf-8');

class Next
{
	//初始化应用程序
	static function runApp($siteid)
	{
		// 调用相应模块的控制器动作
		application::instance($siteid)->dispatching();
	}
	
	//初始化 API 接口
	static function runApi()
	{
		// 调用相应模块的控制器动作
		api::instance()->dispatching();
	}
	
	/**
	 * 加载类文件函数
	 * @param string $classname 类名
	 * @param bool $initialize 是否初始化，1 实例化| 0 禁止实例化，默认为 0
	 */
	static function loadClass($classname, $initialize = 0) 
	{
        static $classes = array();
        $key = md5($classname);
        if (isset($classes[$key])) 
        {
            if (!empty($classes[$key])) 
            {
                return $classes[$key];
            } 
            else 
            {
                return true;
            }
        }
        if (isset(self::$_coreClasses[$classname]))
        {
			if(file_exists(Next_PATH.'class'.DIRECTORY_SEPARATOR.self::$_coreClasses[$classname]))
			{
				include Next_PATH.'class'.DIRECTORY_SEPARATOR.self::$_coreClasses[$classname];
			}
            else
			{
				NLOG::error('    ['.Next_PATH.'class'.DIRECTORY_SEPARATOR.self::$_coreClasses[$classname].']['.$classname.']ClassFile does not exist');
			}
            if ($initialize) 
            {
                $classes[$key] = new $classname;
                return $classes[$key];
            } 
        }
        elseif(strpos($classname, '_') == true)
        {
			$classpath = str_replace('_',DIRECTORY_SEPARATOR,$classname); //example: modulename_classname_classtype
			if (file_exists(self::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$classpath.'.php')) 
			{
				include self::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$classpath.'.php';
				// 载入文件后判断指定的类或接口是否已经定义
				if (!class_exists($classname, false) && !interface_exists($classname, false))
				{
					NLOG::error('    ['.self::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$classpath.'.php]['.$classname.']Class or Interface Not Defined');
				}
				if ($initialize) 
				{
					$classes[$key] = new $classname;
					return $classes[$key];
				}
			}
			else
			{
				NLOG::error('    ['.self::config('system', 'module_path', APP_PATH.'module'.DIRECTORY_SEPARATOR).$classpath.'.php]ClassFile does not exist');
			}
        }
		else
		{
			NLOG::error('    ['.$classname.']Class does not exist');
		}
	}
	
	/**
     * 用于 Next 的类自动载入，不需要由开发者调用
     *
     * @param string $classname
     */
    static function autoloadClass($classname)
    {
        self::loadClass($classname);
    }
	
	/**
	 * 用于 Next 的函数自动载入，不需要由开发者调用
	 * 
	 */
	static function autoloadFunc()
	{
		$auto_funcs = glob(Next_PATH.'function'.DIRECTORY_SEPARATOR.'*.php');
		if(!empty($auto_funcs) && is_array($auto_funcs))
		{
			foreach($auto_funcs as $func_path)
			{
				include $func_path;
			}
		}
	}

	/**
     * 注册或取消注册一个自动类载入方法
     *
     * @param string $class 提供自动载入服务的类
     * @param boolean $enabled 启用或禁用该服务
     */
    static function registerAutoloader($class = 'Next', $enabled = true)
    {
		if(!function_exists('spl_autoload_register'))
		{
			NLOG::error('    spl_autoload does not exist in this PHP installation');
		}
        if ($enabled === true)
        {
            spl_autoload_register(array($class, 'autoloadClass'));
        }
        else
        {
            spl_autoload_unregister(array($class, 'autoloadClass'));
        }
    }
	
	/**
     * 返回应用程序基础配置的内容
     *
	 * @param string $item 配置组。如果没有提供 $item 参数，则返回所有配置的内容
     * @param string $key  要获取的配置荐
	 * @param string $default  默认配置。当获取配置项目失败时该值发生作用。
     * @param string $item
     *
     * @return mixed
     */
    static function config($item = null, $key = '', $default = '')
    {
    	static $_app_config = array();
    	if(empty($_app_config))
    	{
    		// 初始化应用程序设置
			$_app_config = include Next_PATH.'data'.DIRECTORY_SEPARATOR.'config.php';
    	}

		if(isset($_app_config[$item]))
		{	
			if(empty($key))
			{
				return $_app_config[$item];
			}
			elseif(isset($_app_config[$item][$key]))
			{
				return $_app_config[$item][$key];
			}
			else
			{
				return $default;
			}
		}
		elseif($item)
		{
			if($key)
			{
				return $default;
			}
			else
			{
				NLOG::error('    '.$item.'-'.$key.' item does not exist in the configuration file');
			}
		}
		else
		{
			return $_app_config;
		}
    }

    /**
     * 输出自定义错误
     *
     * @param $errno 错误号
     * @param $errstr 错误描述
     * @param $errfile 报错文件地址
     * @param $errline 错误行号
     * @return string 错误提示
     */
    static function my_error_handler($error_level, $errstr, $errfile, $errline, $error_context)
    {
        NLOG::error('    '.$error_level.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline);
        die();
    }
	
	/**
	 * @var array 注册 NextPHP 核心类
	 */
	private static $_coreClasses = array(
		'application' => 'application.php',
		'context' => 'context.php',
		'NACL' => 'acl.php',
		'sessionFile' => 'sessionFile.php',
		'NLOG' => 'log.php',
		'controller' => 'controller.php',
		'NView' => 'view.php',
		'form' => 'form.php',
		'api' => 'api.php',
		'sessionFile' => 'sessionFile.php',
		'checkcode' => 'checkcode.php',
		'model' => 'model.php',
		'dbFactory' => 'dbFactory.php',
		'dbMysql' => 'dbMysql.php',
		'dbMssql' => 'dbMssql.php',
		'validator' => 'validator.php',
		'ChromePhp' => 'ChromePhp.php',
		'cacheFactory' => 'cacheFactory.php',
		'cacheFile' => 'cacheFile.php',
		'tree' => 'tree.php',
		'attachment' => 'attachment.php',
		'image' => 'image.php',
		'dir' => 'dir.php',
		'iconv' => 'iconv.php',
		'arr' => 'arr.php'
	);
}

?>
