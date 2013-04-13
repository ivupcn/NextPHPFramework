<?php
class user_controller_acl extends admin_class_controller
{
	function action_setting()
	{
		$role_op = new user_class_roleop();
		if($this->_context->isPOST())
		{
			if(!isset($_POST['menuid'])) $_POST['menuid'] = array();
			if (is_array($_POST['menuid']) && count($_POST['menuid']) > 0)
			{
				user_model_rolepriv::model()->delete(array('siteid'=>ROUTE_S));
				$menuinfo = admin_model_menu::model()->select('','id,m,c,a,data,sys');
				foreach ($menuinfo as $_v) $menu_info[$_v['id']] = $_v;
				foreach($_POST['menuid'] as $menuid => $roles)
				{
					$info = array();
					$info = $role_op->get_menuinfo(intval($menuid),$menu_info);
					unset($info['sys']);
					if(!empty($roles['roleid']))
					{
						$info['roleid'] = implode(',',array_unique($roles['roleid']));
					}
					if(!empty($roles['global_acl']))
					{
						$info['roleid'] = $roles['global_acl'];
					}
					if(empty($roles['roleid']) && empty($roles['global_acl']))
					{
						continue;
					}
					$info['siteid'] = ROUTE_S; 
					$info['menuid'] = $menuid;
					user_model_rolepriv::model()->insert($info);
				}
			}
			else
			{
				user_model_rolepriv::model()->delete(array('siteid'=>ROUTE_S));
			}
			$this->_app->showmessage('200','操作成功！',$this->_context->url('acl::setting@user'),'','user_acl_setting');
		}
		else
		{
			$tree = new tree();
			$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
			$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			$userid = $_SESSION['userid'];
			$email = $_SESSION['email'];
			$roles = getcache('role_'.ROUTE_S,'user');
			$result = admin_model_menu::model()->select(array('sys'=>0),'*','','listorder ASC,id ASC');
			$priv_data = user_model_rolepriv::model()->select(array('siteid'=>ROUTE_S)); //获取权限表数据
			$array = array();
			$global_acl = array('ACL_EVERYONE','ACL_HAS_ROLE','ACL_NO_ROLE');
			foreach($result as $r)
			{
				$r['role_list'] = '';
				if(in_array($r['id'], array(1,2,3)))
				{
					$r['role_list'] = '';
				}
				else
				{
					$priv_roleid = $role_op->get_menu_priv_info(intval($r['id']),$priv_data);
					if($r['sys'] == 0)
					{
						$r['role_list'] = '<select name="menuid['.$r['id'].'][global_acl]"><option value="">请选择</option>';
						if($priv_roleid == 'ACL_HAS_ROLE')
						{
							$r['role_list'] .= '<option value="ACL_HAS_ROLE" selected>HAS_ROLE</option>';
						}
						else
						{
							$r['role_list'] .= '<option value="ACL_HAS_ROLE">HAS_ROLE</option>';
						}
						if($priv_roleid == 'ACL_NO_ROLE')
						{
							$r['role_list'] .= '<option value="ACL_NO_ROLE" selected>NO_ROLE</option>';
						}
						else
						{
							$r['role_list'] .= '<option value="ACL_NO_ROLE">NO_ROLE</option>';
						}
						if($priv_roleid == 'ACL_EVERYONE')
						{
							$r['role_list'] .= '<option value="ACL_EVERYONE" selected>EVERYONE</option>';
						}
						else
						{
							$r['role_list'] .= '<option value="ACL_EVERYONE">EVERYONE</option>';
						}
						$r['role_list'] .= '</select>';
					}
					else
					{
						$r['role_list'] .= '<label><input type="checkbox" name="menuid['.$r['id'].'][roleid][]" value="1" onclick="this.checked=!this.checked" class="textInput readonly" checked />超级管理员</label>';
					}
					if($r['acl_type'] == 0)
					{
						foreach($roles as $rk => $rv)
						{
							if(in_array($rk, normalize($priv_roleid)))
							{
								$checked = ' checked ';
							}
							else
							{
								$checked = '';
							}
							if($rk !='1' && $r['sys'] != '1')
							{
								$r['role_list'] .= '<label><input type="checkbox" name="menuid['.$r['id'].'][roleid][]" value="'.$rk.'"'.$checked.' />'.$rv.'</label>';
							}
						}
					}
				}
				$r['sys'] = $r['sys'] ? '<img src="statics/images/icon/gear_disable.png" width="16" height="16" title="系统菜单" />' : null;
				if($r['m'] == 'm') $r['m'] = '';
				if($r['c'] == 'c') $r['c'] = '';
				if($r['a'] == 'a') $r['a'] = '';
				$r['parentid_node'] = $r['parentid'] ? ' data-tt-parent-id="'.$r['parentid'].'"' : '';
				$array[] = $r;
			}

			$str  = "<tr data-tt-id='\$id' \$parentid_node>
						<td>\$name\$sys</td>
						<td>\$m</td>
						<td>\$c</td>
						<td>\$a</td>
						<td align='left'>\$role_list</td>
					</tr>";
			$tree->init($array);
			$categorys = $tree->get_tree(0, $str);
			$this->config_acl();
			include $this->view('user','acl','setting');
		}
	}

	private function config_acl()
	{
		$acl = $roles = array();
		$rolepriv = user_model_rolepriv::model()->select(array('siteid'=>ROUTE_S));
		foreach($rolepriv as $priv)
		{
			if($priv['c'] =='c' || $priv['a'] =='a')
			{
				continue;
			}
			$acl[$priv['m']][$priv['c']][$priv['a']] = array('role'=>$priv['roleid']);
		}
		setcache('acl_'.ROUTE_S,$acl,'user');
	}
}
?>