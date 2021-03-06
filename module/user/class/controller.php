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
                $r = user_model_user::model()->FIELD('password,siteid,groupid')->WHERE(array('userid'=>$userid))->select(1);
                if($r['password'] == $password)
                {
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
    * 获取当前站点信息
    * @param integer $siteid 站点ID号，为空时取当前站点的信息
    * @return array
    */
    final public static function get_siteinfo($siteid = SITEID)
    {
        $sites = new admin_class_sites();
        return $sites->get_siteinfo();
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID  
     * @param integer $with_self  是否包括他自己
     */
    final public function user_menu($parentid, $with_self = 0)
    {
        $parentid = intval($parentid);
        $result =admin_model_menu::model()->WHERE(array('parentid'=>$parentid,'display'=>1))->ORDER('listorder ASC')->select();
        if($with_self)
        {
            $result2[] = admin_model_menu::model()->WHERE(array('id'=>$parentid))->select(1);
            $result = array_merge($result2,$result);
        }
        //权限检查
        if($_SESSION['roleid'] == 1) return $result;
        $array = array();
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
                $r = user_model_rolepriv::model()->WHERE(array('m'=>$v['m'],'c'=>$v['c'],'a'=>$action,'siteid'=>SITEID))->select(1);
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
            $user = user_model_user::model()->WHERE(array('userid'=>$userid))->select(1);
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