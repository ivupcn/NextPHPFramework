<?php

/**
 * 应用程序的公共控制器基础类
 *
 * 可以在这个类中添加方法来完成应用程序控制器共享的功能。
 */
class attachment_class_controller extends controller
{
    /**
     * 控制器所属的应用程序
     *
     * @var CommunityApp
     */
    protected $_app;

    /**
     * 构造函数
     */
    protected $siteid,$siteinfo,$upload_url,$upload_path,$userid;
    
    function __construct($app)
    {
    	parent::__construct();
        $this->_app = $app;
    	$this->siteid = $_SESSION['siteid'];
    	$this->siteinfo = self::get_siteinfo($this->siteid);
    	$this->upload_url = '/uploadfile/';
    	$this->upload_path = Next::config('system','site_root',APP_PATH.'siteroot'.DIRECTORY_SEPARATOR).$this->siteinfo['dirname'].DIRECTORY_SEPARATOR.'uploadfile'.DIRECTORY_SEPARATOR;
    	$this->userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : exit();
    }

    /**
    * 按角色ID获取默认站点
    */
    final public static function return_siteid()
    {
        $sites = new admin_class_sites();
        $siteid = explode(',',$sites->get_role_siteid($_SESSION['roleid']));
        return current($siteid);
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
     * 获取站点配置信息
     * @param  $siteid 站点id
     */
    final public static function get_site_setting($siteid) {
        $siteinfo = getcache('sitelist', 'admin');
        return string2array($siteinfo[$siteid]['setting']);
    }
}
?>