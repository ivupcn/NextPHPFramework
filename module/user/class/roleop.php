<?php
defined('IN_Next') or exit('No permission resources.');

//定义在后台
defined('IN_ADMIN') or define('IN_ADMIN',true);
class user_class_roleop
{	
	/**
	 * 获取角色中文名称
	 * @param int $roleid 角色ID
	 */
	public function get_rolename($roleid)
	{
		$roleid = intval($roleid);
		$search_field = '`roleid`,`rolename`';
		$info = user_model_role::model()->FIELD('roleid,rolename')->WHERE(array('roleid'=>$roleid))->select(1);
		return $info;
	}
		
	/**
	 * 检查角色名称重复
	 * @param $name 角色组名称
	 */
	public function checkname($roleid,$name,$siteid,$op_status)
	{
		if($op_status == 'add')
		{
			$info = user_model_role::model()->FIELD('roleid')->WHERE(array('rolename'=>$name,'siteid'=>$siteid))->select(1);
		}
		elseif($op_status == 'edit')
		{
			$info = user_model_role::model()->FIELD('roleid')->WHERE(array('rolename'=>$name, 'siteid'=>$siteid, 'roleid'=>array('NOTIN', $roleid)))->select(1);
		}
		if($info['roleid'])
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 获取菜单表信息
	 * @param int $menuid 菜单ID
	 * @param int $menu_info 菜单数据
	 */
	public function get_menuinfo($menuid,$menu_info)
	{
		$menuid = intval($menuid);
		unset($menu_info[$menuid]['id']);
		return $menu_info[$menuid];
	}

	/**
	 *  检查指定菜单是否有权限
	 * @param int $menuid 菜单ID
	 * @param int $priv_data 菜单权限数据
	 */
	public function get_menu_priv_info($menuid,$priv_data)
	{
		$menuid = intval($menuid);
		foreach($priv_data as $priv)
		{
			if($priv['menuid'] == $menuid)
			{
				return $priv['roleid'];
			}
		}
	}
	
	/**
	 *  检查指定菜单是否有权限
	 * @param array $data menu表中数组
	 * @param int $roleid 需要检查的角色ID
	 */
	public function is_checked($data,$roleid,$siteid,$priv_data)
	{
		$priv_arr = array('m','c','a','data');
		if($data['m'] == '') return false;
		foreach($data as $key=>$value)
		{
			if(!in_array($key,$priv_arr)) unset($data[$key]);
		}
		$data['roleid'] = $roleid;
		$data['siteid'] = $siteid;
		$info = in_array($data, $priv_data);
		if($info)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	/**
	 * 是否为设置状态
	 */
	public function is_setting($siteid,$roleid)
	{
		$siteid = intval($siteid);
		$roleid = intval($roleid);
		$result = user_model_rolepriv::model()->WHERE(array('siteid'=>$siteid, 'roleid'=>$roleid, 'm'=>array('NEQ', '')))->select(1);
		return $result ? true : false;
	}
	/**
	 * 获取菜单深度
	 * @param $id
	 * @param $array
	 * @param $i
	 */
	public function get_level($id,$array=array(),$i=0)
	{
		foreach($array as $n=>$value)
		{
			if($value['id'] == $id)
			{
				if($value['parentid']== '0') return $i;
				$i++;
				return $this->get_level($value['parentid'],$array,$i);
			}
		}
	}
}
?>