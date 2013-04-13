<?php
/**
 *  context.php context 封装了运行时上下文。
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-01
 * 
 * context 封装了运行时上下文：
 * - 主要封装了请求参数和请求状态等功能。
 * - 使用了单子设计模式，因此只能使用 context::instance() 来获得对象的唯一实例。
 * - 实现了 ArrayAccess 接口，可以将 QContext 对象当作数组一样使用。
 * - -  @code php
 * - -  if (isset($context['title']))
 * - - {
 * - -     echo $context['title'];
 * - - }
 * - -  @endcode
 */
class context implements ArrayAccess
{
	/**
     * 指示 UDI 中的部分
     */
	// UDI 中的模块
	const UDI_MODULE     = 'm';
    // UDI 中的控制器
    const UDI_CONTROLLER = 'c';
    // UDI 中的动作
    const UDI_ACTION     = 'a';
    // UDI 中的站点ID
    const UDI_SITEID     = 's';
	
	/**
     * 指示 UDI 的默认值
     */
	// 默认的模块
    const UDI_DEFAULT_MODULE     = 'index';
    // 默认控制器
    const UDI_DEFAULT_CONTROLLER = 'index';
    // 默认动作
    const UDI_DEFAULT_ACTION     = 'init';
    // 默认站点ID
    const UDI_DEFAULT_SITEID     = 1;
	
	/**
     * UDI 的默认值
     */
    private static $_udi_defaults = array(
        self::UDI_MODULE => self::UDI_DEFAULT_MODULE,
        self::UDI_CONTROLLER => self::UDI_DEFAULT_CONTROLLER,
        self::UDI_ACTION => self::UDI_DEFAULT_ACTION,
        self::UDI_SITEID => self::UDI_DEFAULT_SITEID
    );
	
	/**
     * 请求包含的模块名
     *
     * 为了性能原因，$module_name 设置为了 public 成员变量。
     * 但开发者应该使用 changeRequestUDI() 方法来修改 $module_name 等变量。
     *
     * @var string
     */
    public $module_name;
	
	/**
     * 请求包含的控制器名称
     *
     * @var string
     */
    public $controller_name;

    /**
     * 请求包含的动作名
     *
     * @var string
     */
    public $action_name;

    /**
     * 请求包含的站点名
     *
     * @var string
     */
    public $siteid_name;
    
	 /**
     * 附加的参数
     *
     * @var array
     */
    private $_params = array();
	
	/**
     * 构造函数
     */
    private function __construct()
    {
        // 从 $_GET 中提取请求参数
        $keys = array_keys($_GET);
        if (!empty($keys))
        {
            $keys = array_combine($keys, $keys);
            $keys = array_change_key_case($keys);
        }
		
		$udi = array();
		$udi[self::UDI_MODULE]     = (isset($keys[self::UDI_MODULE])) ? $_GET[$keys[self::UDI_MODULE]] : null;
        $udi[self::UDI_CONTROLLER] = (isset($keys[self::UDI_CONTROLLER])) ? $_GET[$keys[self::UDI_CONTROLLER]] : null;
        $udi[self::UDI_ACTION]     = (isset($keys[self::UDI_ACTION])) ? $_GET[$keys[self::UDI_ACTION]] : null;
		$udi[self::UDI_SITEID]     = (isset($keys[self::UDI_SITEID])) ? $_GET[$keys[self::UDI_SITEID]] : self::get_cookie('siteid',1);

        $this->changeRequestUDI($udi);
    }
	
	/**
     * 返回 QContext 对象的唯一实例
     *
     * @code php
     * $context = context::instance();
     * @endcode
     *
     * @return context context 对象的唯一实例
     */
    static function instance()
    {
        static $instance;
        if (is_null($instance)) $instance = new context();
        return $instance;
    }

    /**
     * 设置 cookie
     * @context string $var     变量名
     * @context string $value   变量值
     * @context int $time    过期时间
     */
    public static function set_cookie($var, $value = '', $time = 0)
    {
        $time = $time > 0 ? $time : ($value == '' ? SYS_TIME - 3600 : 0);
        $s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
        $var = Next::config('system','cookie_pre', 'Next_').$var;
        $_COOKIE[$var] = $value;
        if (is_array($value))
        {
            foreach($value as $k=>$v)
            {
                setcookie($var.'['.$k.']', sys_auth($v, 'ENCODE'), $time, Next::config('system','cookie_path',''), Next::config('system','cookie_domain',''), $s);
            }
        }
        else
        {
            setcookie($var, sys_auth($value, 'ENCODE'), $time, Next::config('system','cookie_path',''), Next::config('system','cookie_domain',''), $s);
        }
    }

    /**
     * 获取通过 set_cookie 设置的 cookie 变量 
     * @context string $var 变量名
     * @context string $default 默认值 
     * @return mixed 成功则返回cookie 值，否则返回 false
     */
    public static function get_cookie($var, $default = '')
    {
        $var = Next::config('system','cookie_pre','Next_').$var;
        return isset($_COOKIE[$var]) ? sys_auth($_COOKIE[$var], 'DECODE') : $default;
    }
	
	/**
     * 魔法方法，访问请求参数
     *
     * __get() 魔法方法让开发者可以用 $context->parameter 的形式访问请求参数。
     * 如果指定的参数不存在，则返回 null。
     *
     * @code php
     * $title = $context->title;
     * @endcode
     *
     * 查找请求参数的顺行是 $_GET、$_POST 和 QContext 对象附加参数。
     *
     * @param string $parameter 要访问的请求参数
     *
     * @return mixed 参数值
     */
    function __get($parameter)
    {
        return $this->query($parameter);
    }

    /**
     * 魔法方法，设置附加参数
     *
     * 与 __get() 魔法方法不同，__set() 仅仅设置 context 对象附加参数。
     * 因此当 $_GET 或 $_POST 中包含同名参数时，用 __set() 设置的参数值
     * 只能使用 context::param() 方法来获得。
     *
     * @code php
     * $context->title = $title;
     * echo $context->param('title');
     * @endcode
     *
     * @param string $parameter 要设置值的参数名
     * @param mixed $value 参数值
     */
    function __set($parameter, $value)
    {
        $this->changeParam($parameter, $value);
    }

    /**
     * 魔法方法，确定是否包含指定的参数
     *
     * @param string $parameter 要检查的参数
     *
     * @return boolean 是否具有指定参数
     */
    function __isset($parameter)
    {
        return $this->offsetExists($parameter);
    }

    /**
     * 删除指定的附加参数
     *
     * __unset() 魔法方法只影响 context 对象的附加参数。
     *
     * @code php
     * unset($context['title']);
     * // 此时读取 title 附加参数将获得 null
     * echo $context->param('title');
     * @endcode
     *
     * @param string $parameter 要删除的参数
     */
    function __unset($parameter)
    {
        unset($this->_params[$parameter]);
    }
	
	/**
     * 确定是否包含指定的参数，实现 ArrayAccess 接口
     *
     * @code php
     * echo isset($context['title']);
     * @endcode
     *
     * @param string $parameter 要检查的参数
     *
     * @return boolean 参数是否存在
     */
    function offsetExists($parameter)
    {
        if (isset($_GET[$parameter]))
            return true;
        elseif (isset($_POST[$parameter]))
            return true;
        else
            return isset($this->_params[$parameter]);
    }
	
	/**
     * 设置附加参数，实现 ArrayAccess 接口
     *
     * 该方法功能同 __set() 魔法方法。
     *
     * @code php
     * $context['title'] = $title;
     * echo $context->param('title');
     * @endcode
     *
     * @param string $parameter 要设置的参数
     * @param mixed $value 参数值
     */
    function offsetSet($parameter, $value)
    {
        $this->changeParam($parameter, $value);
    }

    /**
     * 访问请求参数，实现 ArrayAccess 接口
     *
     * @code php
     * $title = $context['title'];
     * @endcode
     *
     * @param string $parameter 要访问的请求参数
     *
     * @return mixed 参数值
     */
    function offsetGet($parameter)
    {
        return $this->query($parameter);
    }

    /**
     * 取消附加参数，实现 ArrayAccess 接口
     *
     * 同 __unset() 方法，QContext::offsetUnset() 只影响 QContext 对象的附加参数。
     *
     * @code php
     * unset($context['title']);
     * @endcode
     *
     * @param string $parameter 要取消的附加参数
     */
    function offsetUnset($parameter)
    {
        unset($this->_params[$parameter]);
    }
	
	/**
     * 魔法方法，访问请求参数
     *
     * context::query() 方法让开发者可以用 $context->parameter 的形式访问请求参数。
     * 如果指定的参数不存在，则返回 $default 参数指定的默认值。
     *
     * @code php
     * $title = $context->query('title', 'default title');
     * @endcode
     *
     * 查找请求参数的顺行是 $_GET、$_POST 和 QContext 对象附加参数。
     *
     * @param string $parameter 要访问的请求参数
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function query($parameter, $default = null)
    {
        if (isset($_GET[$parameter]))
            return $_GET[$parameter];
        elseif (isset($_POST[$parameter]))
            return $_POST[$parameter];
        elseif (isset($this->_params[$parameter]))
            return $this->_params[$parameter];
        else
            return $default;
    }

    /**
     * 获得 GET 数据
     *
     * 从 $_GET 中获得指定参数，如果参数不存在则返回 $default 指定的默认值。
     *
     * @code php
     * $title = $context->get('title', 'default title');
     * @endcode
     *
     * 如果 $parameter 参数为 null，则返回整个 $_GET 的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function get($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_GET;
        return isset($_GET[$parameter]) ? $_GET[$parameter] : $default;
    }

    /**
     * 获得 POST 数据
     *
     * 从 $_POST 中获得指定参数，如果参数不存在则返回 $default 指定的默认值。
     *
     * @code php
     * $body = $context->post('body', 'default body');
     * @endcode
     *
     * 如果 $parameter 参数为 null，则返回整个 $_POST 的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function post($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_POST;
        return isset($_POST[$parameter]) ? $_POST[$parameter] : $default;
    }

    /**
     * 从 $_SERVER 查询服务器运行环境数据
     *
     * 如果参数不存在则返回 $default 指定的默认值。
     *
     * @code php
     * $request_time = $context->server('REQUEST_TIME');
     * @endcode
     *
     * 如果 $parameter 参数为 null，则返回整个 $_SERVER 的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function server($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_SERVER;
        return isset($_SERVER[$parameter]) ? $_SERVER[$parameter] : $default;
    }

    /**
     * 从 $_ENV 查询服务器运行环境数据
     *
     * 如果参数不存在则返回 $default 指定的默认值。
     *
     * @code php
     * $os_type = $context->env('OS', 'non-win');
     * @endcode
     *
     * 如果 $parameter 参数为 null，则返回整个 $_ENV 的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function env($parameter = null, $default = null)
    {
        if (is_null($parameter))
            return $_ENV;
        return isset($_ENV[$parameter]) ? $_ENV[$parameter] : $default;
    }

    /**
     * 设置 context 对象的附加参数
     *
     * @code php
     * $context->changeParam('arg', $value);
     * @endcode
     *
     * @param string $parameter 要设置的参数名
     * @param mixed $value 参数值
     *
     * @return QContext 返回 context 对象本身，实现连贯接口
     */
    function changeParam($parameter, $value)
    {
        $this->_params[$parameter] = $value;
    }
	
	/**
     * 获得 context 对象的附加参数
     *
     * 如果参数不存在则返回 $default 指定的默认值。
     *
     * @code php
     * $value = $context->param('arg', 'default value');
     * @endcode
     *
     * 如果 $parameter 参数为 null，则返回所有附加参数的内容。
     *
     * @param string $parameter 要查询的参数名
     * @param mixed $default 参数不存在时要返回的默认值
     *
     * @return mixed 参数值
     */
    function param($parameter, $default = null)
    {
        if (is_null($parameter))
		{
			return $this->_params;
		}
        else
		{
			return isset($this->_params[$parameter]) ? $this->_params[$parameter] : $default;
		}
    }
	
	/**
     * 返回所有上下文参数
     *
     *
     * @return array
     */
    function params()
    {
        return $this->_params;
    }
	
	/**
     * 返回请求使用的方法
     *
     * @return string
     */
    function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 是否是 GET 请求
     *
     * @return boolean
     */
    function isGET()
    {
        return $this->requestMethod() == 'GET';
    }

    /**
     * 是否是 POST 请求
     *
     * @return boolean
     */
    function isPOST()
    {
        return $this->requestMethod() == 'POST';
    }

    /**
     * 是否是 PUT 请求
     *
     * @return boolean
     */
    function isPUT()
    {
        return $this->requestMethod() == 'PUT';
    }

    /**
     * 是否是 DELETE 请求
     *
     * @return boolean
     */
    function isDELETE()
    {
        return $this->requestMethod() == 'DELETE';
    }

    /**
     * 是否是 HEAD 请求
     *
     * @return boolean
     */
    function isHEAD()
    {
        return $this->requestMethod() == 'HEAD';
    }

    /**
     * 是否是 OPTIONS 请求
     *
     * @return boolean
     */
    function isOPTIONS()
    {
        return $this->requestMethod() == 'OPTIONS';
    }

    /**
     * 判断 HTTP 请求是否是通过 XMLHttp 发起的
     *
     * @return boolean
     */
    function isAJAX()
    {
        return strtolower($this->header('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    /**
     * 判断 HTTP 请求是否是通过 Flash 发起的
     *
     * @return boolean
     */
    function isFlash()
    {
        $agent = strtolower($this->header('USER_AGENT'));
        return strpos($agent, 'shockwave flash') !== false || strpos($agent, 'adobeair') !== false;
    }

    /**
     * 返回请求的原始内容
     *
     * @return string
     */
    function requestRawBody()
    {
        $body = file_get_contents('php://input');
        return (strlen(trim($body)) > 0) ? $body : false;
    }

    /**
     * 返回 HTTP 请求头中的指定信息，如果没有指定参数则返回 false
     *
     * @param string $header 要查询的请求头参数
     *
     * @return string 参数值
     */
    function header($header)
    {
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (!empty($_SERVER[$temp])) return $_SERVER[$temp];

        if (function_exists('apache_request_headers'))
        {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) return $headers[$header];
        }

        return false;
    }

    /**
     * 返回当前请求的参照 URL
     *
     * @return string 当前请求的参照 URL
     */
    function referer()
    {
        return $this->header('REFERER');
    }

    /**
     * 构造 url
     *
     * 用法：
     *
     * @code php
     * url(UDI, [附加参数数组], [路由名])
     * @endcode
     *
     * UDI 是统一目的地标识符（Uniform Destination Identifier）的缩写。
     * UDI 由控制器、动作、名字空间以及模块名组成，采用如下的格式：
     *
     * @code php
     * controller::action@module
     * @endcode
     *
     * UDI 字符串中，每一个部分都是可选的。
     * 如果没有提供控制器和动作名，则使用当前的控制器和默认动作名（index）代替。
     * 同样，如果未提供模块名和名字空间，均使用当前值代替。
     *
     * UDI 字符串写法示例：
     *
     * @code php
     * 'controller'
     * 'controller::action'
     * '::action'
     * 'controller@module'
     * 'controller::action@module'
     * '@module'
     * @endcode
     *
     * 示例：
     * @code php
     * url('posts::edit', array('id' => $post->id()));
     * @endcode
     *
     * $params 参数除了采用数组，还可以是以“/”符号分割的字符串：
     *
     * @code php
     * url('posts::index', 'page/3');
     * url('users::show', 'id/5/profile/yes');
     * @endcode
     *
     * @param string $udi UDI 字符串
     * @param array|string $params 附加参数数组
     *
     * @return string 生成的 URL 地址
     */
    function url($udi, $params = null)
    {
        $udi = $this->normalizeUDI($udi);
        if(!is_array($params))
        {
            $arr = normalize($params, '/');
            $params = array();
            while ($key = array_shift($arr))
            {
                $value = array_shift($arr);
                $params[$key] = $value;
            }
        }

        $params = array_filter(array_merge($udi, $params), 'strlen');
        $url = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://' .$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
        if (!empty($params))
        {
            $url .= '?' . http_build_query($params, '', '&');
        }
        return $url;
    }
	
	/**
     * 返回规范化以后的 UDI 数组
     *
     * @code php
     * $udi = array(
     *     context::UDI_MODULE     => '',
     *     context::UDI_CONTROLLER => '',
     *     context::UDI_ACTION     => '',
     *     context::UDI_SITEID     => ''
     * );
     *
     * // 输出
     * // array(
     * //     module:     default
     * //     controller: default
     * //     action:     index
     * //     siteid:     1
     * // )
     * dump($context->normalizeUDI($udi));
     *
     * $udi = 'posts::edit#1@admin';
     * // 输出
     * // array(
     * //     module:     admin
     * //     controller: posts
     * //     action:     edit
     * //     siteid:     1
     * // )
     * dump($context->normalizeUDI($udi));
     * @endcode
     *
     * 如果要返回字符串形式的 UDI，设置 $return_array 参数为 false。
     *
     * @param string|array $udi 要处理的 UDI
     * @param boolean $return_array 是否返回数组形式的 UDI
     *
     * @return array 处理后的 UDI
     */
    function normalizeUDI($udi, $return_array = true)
    {
        if (!is_array($udi))
        {
            // 特殊处理 ""UDI解析
            // ""返回当前动作
            if(!is_string($udi) || $udi == '')
            {
                $module_name = $this->module_name;
                $controller_name = $this->controller_name;
                $action_name = $this->action_name;
                $siteid_name = $this->siteid_name;
            }
            elseif($udi == '::')
            {
                $module_name = $this->module_name;
                $controller_name = $this->controller_name;
                $action_name = self::$_udi_defaults[self::UDI_ACTION];
                $siteid_name = $this->siteid_name;
            }
            else
            {
                if (strpos($udi, '::') !== false)
                {
                    $arr = explode('::', $udi);
                    $controller_name = array_shift($arr);
                    $udi = array_shift($arr);
                }
                else
                {
                    $controller_name = $this->controller_name;
                }
                if(strpos($udi, '@') !== false)
                {
                    $arr = explode('@', $udi);
                    $module_name = array_pop($arr);
                    $udi = array_pop($arr);
                }
                else
                {
                    $module_name = $this->module_name;
                }

                $arr = explode('#', $udi);
                $action_name = array_shift($arr);
                $siteid_name = array_shift($arr);
            }

            $udi = array(
                self::UDI_MODULE     => $module_name,
                self::UDI_CONTROLLER => $controller_name,
                self::UDI_ACTION     => $action_name,
                self::UDI_SITEID     => $siteid_name
            );
        }

        if (empty($udi[self::UDI_MODULE]))
        {
            $udi[self::UDI_MODULE] = $this->module_name;
        }
        if (empty($udi[self::UDI_CONTROLLER]))
        {
            $udi[self::UDI_CONTROLLER] = $this->controller_name;
        }
        if (empty($udi[self::UDI_ACTION]))
        {
            $udi[self::UDI_ACTION] = self::UDI_DEFAULT_ACTION;
        }
        if (empty($udi[self::UDI_SITEID]))
        {
            $udi[self::UDI_SITEID] = $this->siteid_name;
        }
        foreach (self::$_udi_defaults as $key => $value)
        {
            if (empty($udi[$key]))
            {
                $udi[$key] = $value;
            }
            else
            {
                $udi[$key] = preg_replace('/[^a-z0-9]+/', '', strtolower($udi[$key]));
            }
        }

        if (!$return_array)
        {
            return $udi[self::UDI_CONTROLLER].'::'.$udi[self::UDI_ACTION].'#'.$udi[self::UDI_SITEID].'@'.$udi[self::UDI_MODULE];
        }
        else
        {
            return $udi;
        }
    }
	
	/**
     * 返回当前请求对应的 UDI
     *
     * 将当前请求中包含的模块、控制器和动作名提取出来，构造为一个 UDI。
     *
     * @code php
     * dump($context->requestUDI());
     * @endcode
     *
     * @param boolean $return_array 是否返回数组形式的 UDI
     *
     * @return string|array 对应当前请求的 UDI
     */
    function requestUDI($return_array = true)
    {
        return $this->normalizeUDI('::'.$this->action_name, $return_array);
    }
	
	/**
     * 将 context 对象保存的请求参数设置为 UDI 指定的值
     *
     * @code php
     * $context->changeRequestUDI('posts::edit');
     * // 将输出 posts
     * echo $context->controller_name;
     * @endcode
     *
     * @param array|string $udi 要设置的 UDI
     *
     * @return context 返回 context 对象本身，实现连贯接口
     */
    function changeRequestUDI($udi)
    {
        $udi = $this->normalizeUDI($udi);
		
		$this->module_name     = $udi[self::UDI_MODULE];
        $this->controller_name = $udi[self::UDI_CONTROLLER];
        $this->action_name     = $udi[self::UDI_ACTION];
        $this->siteid_name     = $udi[self::UDI_SITEID];
        return $this;
    }
}
?>
