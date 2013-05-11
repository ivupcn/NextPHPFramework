<?php
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string)
{
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) 
{
    if(!is_array($string)) return stripslashes($string);
    foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
    return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) 
{
    if(!is_array($string)) return htmlspecialchars($string);
    foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    return $string;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) 
{
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'&qpos;',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'{',$string);
    $string = str_replace('}','}',$string);
    $string = str_replace('\\','',$string);
    return $string;
}

/**
* 将字符串转换为数组
*
* @param    string  $data   字符串
* @return   array   返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) 
{
    if($data == '') return array();
    @eval("\$array = $data;");
    return $array;
}
/**
* 将数组转换为字符串
*
* @param    array   $data       数组
* @param    bool    $isformdata 如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return   string  返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) 
{
    if($data == '') return '';
    if($isformdata) $data = new_stripslashes($data);
    return addslashes(var_export($data, TRUE));
}

/**
* 对字符串或数组进行格式化，返回格式化后的数组
*
* $input 参数如果是字符串，则首先以“,”为分隔符，将字符串转换为一个数组。
* 接下来对数组中每一个项目使用 trim() 方法去掉首尾的空白字符。最后过滤掉空字符串项目。
*
* 该方法的主要用途是将诸如：“item1, item2, item3” 这样的字符串转换为数组。
*
* @code php
* $input = 'item1, item2, item3';
* $output = normalize($input);
* // $output 现在是一个数组，结果如下：
* // $output = array(
* //   'item1',
* //   'item2',
* //   'item3',
* // );
*
* $input = 'item1|item2|item3';
* // 指定使用什么字符作为分割符
* $output = normalize($input, '|');
* @endcode
*
* @param array|string $input 要格式化的字符串或数组
* @param string $delimiter 按照什么字符进行分割
*
* @return array 格式化结果
*/
function normalize($input, $delimiter = ',')
{
	if (!is_array($input))
	{
		$input = explode($delimiter, $input);
	}
	$input = array_map('trim', $input);
	return array_filter($input, 'strlen');
}
/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}
/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars = '0123456789') 
{
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) 
    {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
* 字符串加密、解密函数
*
*
* @param    string  $txt        字符串
* @param    string  $operation  ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
* @param    string  $key        密钥：数字、字母、下划线
* @param    string  $expiry     过期时间
* @return   string
*/
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0)
{
    $key_length = 4;
    $key = md5($key != '' ? $key : Next::config('system','auth_key','29HTvKg84Veg8VtDdKbs'));
    $fixedkey = md5($key);
    $egiskeys = md5(substr($fixedkey, 16, 16));
    $runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
    $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
    $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

    $i = 0; $result = '';
    $string_length = strlen($string);
    for ($i = 0; $i < $string_length; $i++){
        $result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
    }
    if($operation == 'ENCODE') {
        return $runtokey . str_replace('=', '', base64_encode($result));
    } else {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    }
}

/**
 * 写入配置。
 * @param $name 配置名称
 * @param $data 配置数据
 */
function setconfig($name, $data)
{
    $filepath = Next::config('system','config_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR);
    $filename = $name.'.php';
    if(!is_dir($filepath))
    {
        mkdir($filepath, 0777, true);
    }
    $data = "<?php\nreturn ".var_export($data, true).";\n?>";
    //是否开启互斥锁
    if(Next::config('system', 'lock_ex'))
    {
        $file_size = file_put_contents($filepath.$filename, $data, LOCK_EX);
    }
    else
    {
        $file_size = file_put_contents($filepath.$filename, $data);
    }
    return $file_size ? $file_size : 'false';
}

/**
 * 写入缓存，默认为文件缓存，不加载缓存配置。
 * @param $name 缓存名称
 * @param $data 缓存数据
 * @param $filepath 数据路径（模块名称） caches/cache_$filepath/
 * @param $type 缓存类型[file,memcache,apc]
 * @param $config 配置名称
 * @param $timeout 过期时间
 */
function setcache($name, $data, $filepath='', $type='file', $config='', $timeout=0)
{
    if($config)
    {
        $cacheconfig = Next::config('cache');
        $cache = cacheFactory::get_instance($cacheconfig)->get_cache($config);
    }
    else
    {
        $cache = cacheFactory::get_instance()->get_cache($type);
    }

    return $cache->set($name, $data, $timeout, '', $filepath);
}

/**
 * 读取缓存，默认为文件缓存，不加载缓存配置。
 * @param string $name 缓存名称
 * @param $filepath 数据路径（模块名称） cache/cache_$filepath/
 * @param string $config 配置名称
 */
function getcache($name, $filepath='', $type='file', $config='')
{
    if($config)
    {
        $cacheconfig = Next::config('cache');
        $cache = cacheFactory::get_instance($cacheconfig)->get_cache($config);
    }
    else
    {
        $cache = cacheFactory::get_instance()->get_cache($type);
    }
    return $cache->get($name, '', '', $filepath);
}

/**
 * 删除缓存，默认为文件缓存，不加载缓存配置。
 * @param $name 缓存名称
 * @param $filepath 数据路径（模块名称） cache/cache_$filepath/
 * @param $type 缓存类型[file,memcache,apc]
 * @param $config 配置名称
 */
function delcache($name, $filepath='', $type='file', $config='')
{
    if($config)
    {
        $cacheconfig = Next::config('cache');
        $cache = cacheFactory::get_instance($cacheconfig)->get_cache($config);
    }
    else
    {
        $cache = cacheFactory::get_instance()->get_cache($type);
    }
    return $cache->delete($name, '', '', $filepath);
}
/**
 * 分页函数
 *
 * @param $num 信息总数
 * @param $curr_page 当前分页
 * @param $perpage 每页显示数
 * @param $urlrule URL规则
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
function pages($num, $curr_page, $perpage = 20, $urlrule = array('','page={$page}'), $array = array(),$setpages = 10)
{
    $urlrule = url_par($urlrule[1],$urlrule[0]);
    $multipage = '';
    if($num > $perpage)
    {
        $page = $setpages+1;
        $offset = ceil($setpages/2-1);
        $pages = ceil($num / $perpage);
        if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
        $from = $curr_page - $offset;
        $to = $curr_page + $offset;
        $more = 0;
        
        if($page >= $pages)
        {
            $from = 2;
            $to = $pages-1;
        }
        else
        {
            if($from <= 1)
            {
                $to = $page-1;
                $from = 2;
            }
            elseif($to >= $pages)
            {
                $from = $pages-($page-2);
                $to = $pages-1;
            }
            $more = 1;
        }
        $multipage .= '<a class="a1">共'.$num.'篇</a>';
        if($curr_page>0)
        {
            $multipage .= ' <a href="'.pageurl($urlrule,$curr_page-1, $array).'" class="a1">上一页</a>';
            if($curr_page==1)
            {
                $multipage .= ' <span>1</span>';
            }
            elseif($curr_page>6 && $more)
            {
                $multipage .= ' <a href="'.pageurl($urlrule, 1, $array).'">1</a>..';
            }
            else
            {
                $multipage .= ' <a href="'.pageurl($urlrule, 1, $array).'">1</a>';
            }
        }
        for($i = $from; $i <= $to; $i++)
        {
            if($i != $curr_page)
            {
                $multipage .= ' <a href="'.pageurl($urlrule, $i, $array).'">'.$i.'</a>';
            }
            else
            {
                $multipage .= ' <span>'.$i.'</span>';
            }
        }
        if($curr_page<$pages)
        {
            if($curr_page<$pages-5 && $more)
            {
                $multipage .= ' ..<a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">下一页</a>';
            }
            else
            {
                $multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">下一页</a>';
            }
        }
        elseif($curr_page==$pages)
        {
            $multipage .= ' <span>'.$pages.'</span> <a class="a1">下一页</a>';
        }
        else
        {
            $multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">下一页</a>';
        }
    }
    return $multipage;
}
/**
 * 返回分页路径
 *
 * @param $urlrule 分页规则
 * @param $page 当前页
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 完整的URL路径
 */
function pageurl($urlrule, $page, $array = array())
{
    if(strpos($urlrule, '~'))
    {
        $urlrules = explode('~', $urlrule);
        $urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
    }
    $findme = array('{$page}');
    $replaceme = array($page);
    if (is_array($array)) foreach ($array as $k=>$v)
    {
        $findme[] = '{$'.$k.'}';
        $replaceme[] = $v;
    }
    $url = str_replace($findme, $replaceme, $urlrule);
    $url = str_replace(array('http://','//','~'), array('~','/','http://'), $url);
    return $url;
}

/**
 * URL路径解析，pages 函数的辅助函数
 *
 * @param $par 传入需要解析的变量 默认为，page={$page}
 * @param $url URL地址
 * @return URL
 */
function url_par($par, $url = '')
{
    if($url == '') $url = get_url();
    $pos = strpos($url, '?');
    if($pos === false)
    {
        $url .= '?'.$par;
    }
    else
    {
        $querystring = substr(strstr($url, '?'), 1);
        parse_str($querystring, $pars);
        $query_array = array();
        foreach($pars as $k=>$v)
        {
            if($k != 'page') $query_array[$k] = $v;
        }
        $querystring = http_build_query($query_array).'&'.$par;
        $url = substr($url, 0, $pos).'?'.$querystring;
    }
    return $url;
}

/**
 * 获取当前页面完整URL地址
 */
function get_url()
{
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
    $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt='') {
    $pwd = array();
    $pwd['encrypt'] =  $encrypt ? $encrypt : create_randomstr();
    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
    return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 生成上传附件验证
 * @param $args   参数
 * @param $operation   操作类型(加密解密)
 */

function upload_key($args) {
    $x_auth_key = md5(Next::config('system','auth_key','29HTvKg84Veg8VtDdKbs').$_SERVER['HTTP_USER_AGENT']);
    $authkey = md5($args.$x_auth_key);
    return $authkey;
}

/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 检查id是否存在于数组中
 *
 * @param $id
 * @param $ids
 * @param $s
 */
function check_in($id, $ids = '', $s = ',')
{
    if(!$ids) return false;
    $ids = explode($s, $ids);
    return is_array($id) ? array_intersect($id, $ids) : in_array($id, $ids);
}
?>
