<?php
defined('IN_Next') or exit('No permission resources.');
//定义在用户中心
defined('IN_USER') or define('IN_USER',true);

/**
 * 可以在这个类中添加方法来完成应用程序控制器共享的功能。
 */
class user_class_controller extends controller
{
	/**
     * 控制器所属的应用程序
     *
     * @var CommunityApp
     */
    protected $_app;

    public function __construct($app)
    {
		parent::__construct();
		$this->_app = $app;
        self::check_user();
    }

    /**
    * 判断用户是否已经登陆
    */
    final public static function check_user()
    {
        if(ROUTE_M =='user' && ROUTE_C =='index' && in_array(ROUTE_A, array('login', 'register', 'logout', 'mini', 'send_newmail', 'agreement')))
        {
            return true;
        }
        else
        {
            $x_auth = isset($_SESSION['auth']) ? $_SESSION['auth'] : null;
            //判断是否存在 auth cookie
            if($x_auth)
            {
                $auth_key = md5(Next::config('system', 'auth_key','29HTvKg84Veg8VtDdKbs').$_SERVER['HTTP_USER_AGENT']);
                list($userid, $password) = explode("\t", sys_auth($x_auth, 'DECODE', $auth_key));
                //查询帐号
                $r = user_model_user::model()->get_one(array('userid'=>$userid),'password,siteid,groupid');
                if($r['password'] == $password)
                {
                    if (!defined('SITEID'))
                    {
                       define('SITEID', $r['siteid']);
                    }

                    /**
                    * 此处可以添加与用户组相关的代码
                    */
                }
                else
                {
                    session_unset();
                    session_destroy();
                    Header('Location:'.context::instance()->url('index::login@user'));
                }
            }
            else
            {
                Header('Location:'.context::instance()->url('index::login@user'));
            }
        }
    }

    /**
     * 获取当前的站点ID
     */
    final public static function get_siteid()
    {
        static $siteid;
        if (!empty($siteid)) return $siteid;
        if (isset($_SESSION['siteid']))
        {
            $siteid = $_SESSION['siteid'];
        } else
        {
            $siteid = 1;
        }
        return $siteid;
    }

    /**
    * 获取当前站点信息
    * @param integer $siteid 站点ID号，为空时取当前站点的信息
    * @return array
    */
    final public static function get_siteinfo($siteid = '')
    {
        if ($siteid == '') $siteid = self::get_siteid();
        if (empty($siteid)) return false;
        $sites = new admin_class_sites();
        return $sites->get_siteinfo($siteid);
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID  
     * @param integer $with_self  是否包括他自己
     */
    final public function user_menu($parentid, $with_self = 0)
    {
        $parentid = intval($parentid);
        $result =admin_model_menu::model()->select(array('parentid'=>$parentid,'display'=>1),'*',1000,'listorder ASC');
        if($with_self)
        {
            $result2[] = admin_model_menu::model()->get_one(array('id'=>$parentid));
            $result = array_merge($result2,$result);
        }
        //权限检查
        if($_SESSION['roleid'] == 1) return $result;
        $array = array();
        $siteid = $_SESSION['siteid'];
        foreach($result as $v)
        {
            $action = $v['a'];
            if(preg_match('/^public/',$action))
            {
                $array[] = $v;
            }
            else
            {
                if(preg_match('/^ajax([a-z]+)_/',$action,$_match)) $action = $_match[1];
                $r = user_model_rolepriv::model()->get_one(array('m'=>$v['m'],'c'=>$v['c'],'a'=>$action,'siteid'=>$siteid));
                if($r)
                {
                    $roleid = explode(',', $_SESSION['roleid']);
                    foreach($roleid as $roleid)
                    {
                        if(in_array($roleid, normalize($r['roleid'])))
                        {
                            $array[] = $v;
                            break;
                        }
                        elseif($r['roleid'] == 'ACL_HAS_ROLE')
                        {
                            $array[] = $v;
                            break;
                        }
                    }
                }
            }
        }
        return $array;
    }

    /**
     * 获取用户信息
     * @param integer $userid   用户ID  
     * @param string $field   字段名  
     * @return array $string
     */
    final public function get_userinfo($userid = '', $field = '')
    {
        if($userid)
        {
            $user = user_model_user::model()->get_one(array('userid'=>$userid));
            if($field)
            {
                return $user[$field];
            }
            else
            {
                return $user;
            }
        }
        else
        {
            return false;
        }
    }
}
?>