<?php
class admin_class_sites
{
	/**
	 * 获取站点列表
	 * @param string $roleid 角色ID 留空为获取所有站点列表
	 */
	public function get_list($roleid='')
	{
		$roleid = intval($roleid);
		if(empty($roleid))
		{
			if ($data = getcache('sitelist', 'admin'))
			{
				return $data;
			}
			else
			{
				$this->set_cache();
				return admin_model_site::model()->select();
			}
		}
		else
		{
			if($roleid == 1)
			{
				$sql = '';
			}
			else
			{
				$site_arr = $this->get_role_siteid($roleid);
				$sql = "`siteid` in($site_arr)";
			}
			
			return admin_model_site::model()->select($sql);
		}
	}

	/**
	 * 按角色ID获取站点列表
	 * @param string $roleid 角色ID
	 */	
	
	public function get_role_siteid($roleid)
	{
		$roles = explode(',',$roleid);
		$role_site = getcache('role_site','user');
		$role_arr = array();
		foreach($roles as $role)
		{
			$role_arr[] = $role_site[$role]['siteid'];
		}
		return implode(',', array_unique($role_arr));
	}

	/**
	 * 获取站点的信息
	 * @param $siteid   站点ID
	 */
	public function get_siteinfo($siteid)
	{
	    static $sitelist;
	    if (empty($sitelist)) $sitelist  = getcache('sitelist','admin');
	    return isset($sitelist[$siteid]) ? $sitelist[$siteid] : '';
	}

	/**
	 * 设置站点缓存
	 */
	public function set_cache()
	{
		$list = admin_model_site::model()->select();
		$data = array();
		foreach ($list as $key=>$val)
		{
			$data[$val['siteid']] = $val;
			$data[$val['siteid']]['url'] = 'http://'.$val['domain'].'/';
		}
		setcache('sitelist', $data, 'admin');
	}
}
?>