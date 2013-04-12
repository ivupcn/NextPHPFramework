<?php
defined('IN_Next') or exit('No permission resources.');
//定义在后台
defined('IN_ADMIN') or define('IN_ADMIN',true);

/**
 * 可以在这个类中添加方法来完成应用程序控制器共享的功能。
 */
class admin_class_controller extends controller
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
        self::check_admin();
        if(Next::config('system','admin_url') && $_SERVER["HTTP_HOST"]!= Next::config('system','admin_url'))
        {
            Header("http/1.1 403 Forbidden");
            exit('No permission resources.');
        }
    }

     /**
    * 判断用户是否已经登陆
    */
    final public function check_admin()
    {
        if(ROUTE_M =='admin' && ROUTE_C =='index' && in_array(ROUTE_A, array('login')))
        {
            return true;
        }
        else
        {
            if(!isset($_SESSION['userid']) || !isset($_SESSION['roleid']) || !$_SESSION['userid'] || !$_SESSION['roleid']) Header('Location:'.$this->_context->url('index::login@admin'));
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
    final public function admin_menu($parentid, $with_self = 0)
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
}
?>
