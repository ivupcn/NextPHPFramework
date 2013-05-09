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
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID  
     * @param integer $with_self  是否包括他自己
     */
    final public function admin_menu($parentid, $with_self = 0)
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
}
?>
