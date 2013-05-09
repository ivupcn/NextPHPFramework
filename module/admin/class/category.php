<?php
class admin_class_category
{
	public $categorys;

	public function repair($module)
	{
		if(!$module) return false;
		$data = admin_model_tag::model()->WHERE(array('siteid'=>SITEID,'module'=>$module))->ORDER('listorder ASC,id ASC')->SELECT();
		$this->categorys = $categorys = arr::sortbykey($data, 'id');
		if(is_array($this->categorys))
		{
			foreach($this->categorys as $id => $cat)
			{
				if($cat['type'] == 'link') continue;
				if($cat['parentid'] != 0 && !isset($this->categorys[$cat['parentid']]))
				{
					//删除在非正常显示的栏目
					admin_model_tag::model()->WHERE(array('id'=>$id))->delete();
					continue;
				}
				$arrparentid = $this->get_arrparentid($id);
				$arrchildid = $this->get_arrchildid($id);
				$setting = string2array($cat['setting']);
				$child = is_numeric($arrchildid) ? 0 : 1;
				if($categorys[$id]['arrparentid']!=$arrparentid || $categorys[$id]['arrchildid']!=$arrchildid || $categorys[$id]['child']!=$child)
				{
					admin_model_tag::model()->SET(array('arrparentid'=>$arrparentid,'arrchildid'=>$arrchildid,'child'=>$child))->WHERE(array('id'=>$id))->update();
				}

				$parentdir = $this->get_parentdir($id);
				$tagname = $cat['tagname'];
				$letters = iconv::gbk_to_pinyin($tagname);
				$letter = strtolower(implode('', $letters));
				$listorder = $cat['listorder'] ? $cat['listorder'] : $id;
				
				if(isset($setting['ishtml']))
				{
				//生成静态时
					$url = $this->update_url($module,$id);
					if(!preg_match('/^(http|https):\/\//i', $url))
					{
						$url = 'html/'.$url;
					}
					if($cat['url']!=$url) admin_model_tag::model()->SET(array('url'=>$url))->WHERE(array('id'=>$id))->update();
				}

				if($categorys[$id]['parentdir']!=$parentdir || $categorys[$id]['letter']!=$letter || $categorys[$id]['listorder']!=$listorder)
				{
					admin_model_tag::model()->SET(array('parentdir'=>$parentdir,'letter'=>$letter,'listorder'=>$listorder))->WHERE(array('id'=>$id))->update();
				}
					
			}
		}
		return true;
	}

	/**
	 * 更新缓存
	 */
	public function cache($module)
	{
		if(!$module) return false;
		$categorys = array();
		$models = getcache('model_'.SITEID,'admin');
		foreach ($models as $modelid=>$model)
		{
			$datas = admin_model_tag::model()->FIELD('id,type,items')->WHERE(array('modelid'=>$modelid))->select();
			$array = array();
			foreach ($datas as $r) {
				if($r['type']==0) $array[$r['id']] = $r['items'];
			}
			setcache('category_items_'.$modelid, $array,'commons');
		}
		$array = array();
		$categorys = admin_model_tag::model()->FIELD('id,siteid')->WHERE(array('module'=>$module))->ORDER('listorder ASC')->select();
		foreach ($categorys as $r)
		{
			$array[$r['id']] = $r['siteid'];
		}
		setcache('category_'.$module,$array,'admin');
		$categorys = $this->categorys = array();
		$this->categorys = admin_model_tag::model()->WHERE(array('siteid'=>SITEID, 'module'=>$module))->ORDER('listorder ASC')->select();
		foreach($this->categorys as $r)
		{
			unset($r['module']);
			$setting = string2array($r['setting']);
			$r['create_to_html_root'] = isset($setting['create_to_html_root']) ? $setting['create_to_html_root'] : null;
			$r['ishtml'] = isset($setting['ishtml']) ? $setting['ishtml'] : null;
			$r['content_ishtml'] = isset($setting['content_ishtml']) ? $setting['content_ishtml'] : null;
			$r['category_ruleid'] = isset($setting['category_ruleid']) ? $setting['category_ruleid'] : null;
			$r['show_ruleid'] = isset($setting['show_ruleid']) ? $setting['show_ruleid'] : null;
			$r['workflowid'] = $setting['workflowid'];
			$r['isdomain'] = '0';
			if(!preg_match('/^(http|https):\/\//', $r['url']))
			{
				$r['url'] = $this->siteurl($r['siteid']).$r['url'];
			}
			elseif ($r['ishtml'])
			{
				$r['isdomain'] = '1';
			}
			$categorys[$r['id']] = $r;
		}
		setcache('category_'.$module.'_'.SITEID,$categorys,'admin');
		return true;
	}

	/**
	 * 
	 * 获取父栏目ID列表
	 * @param integer $id              栏目ID
	 * @param array $arrparentid          父目录ID
	 * @param integer $n                  查找的层次
	 */
	private function get_arrparentid($id, $arrparentid = '', $n = 1)
	{
		if($n > 5 || !is_array($this->categorys) || !isset($this->categorys[$id])) return false;
		$parentid = $this->categorys[$id]['parentid'];
		$arrparentid = $arrparentid ? $parentid.','.$arrparentid : $parentid;
		if($parentid)
		{
			$arrparentid = $this->get_arrparentid($parentid, $arrparentid, ++$n);
		}
		else
		{
			$this->categorys[$id]['arrparentid'] = $arrparentid;
		}
		$parentid = $this->categorys[$id]['parentid'];
		return $arrparentid;
	}

	/**
	 * 
	 * 获取子栏目ID列表
	 * @param $id 栏目ID
	 */
	private function get_arrchildid($id)
	{
		$arrchildid = $id;
		if(is_array($this->categorys))
		{
			foreach($this->categorys as $id => $cat)
			{
				if($cat['parentid'] && $id != $id && $cat['parentid']==$id)
				{
					$arrchildid .= ','.$this->get_arrchildid($id);
				}
			}
		}
		return $arrchildid;
	}

	/**
	 * 获取父栏目路径
	 * @param  $id
	 */
	private function get_parentdir($id) {
		if($this->categorys[$id]['parentid']==0) return '';
		$r = $this->categorys[$id];
		$setting = string2array($r['setting']);
		$url = $r['url'];
		$arrparentid = $r['arrparentid'];
		unset($r);
		if (strpos($url, '://')===false) {
			if (isset($setting['creat_to_html_root']))
			{
				return '';
			}
			else
			{
				$arrparentid = explode(',', $arrparentid);
				$arrcatdir = array();
				foreach($arrparentid as $id)
				{
					if($id==0) continue;
					$arrcatdir[] = $this->categorys[$id]['tagdir'];
				}
				return implode('/', $arrcatdir).'/';
			}
		}
		else
		{
			if (isset($setting['create_to_html_root']))
			{
				if (preg_match('/^((http|https):\/\/)?([^\/]+)/i', $url, $matches))
				{
					$url = $matches[0].'/';
					$rs = $this->db->get_one(array('url'=>$url), '`parentdir`,`id`');
					if ($id == $rs['id']) return '';
					else return $rs['parentdir'];
				}
				else
				{
					return '';
				}
			}
			else
			{
				$arrparentid = explode(',', $arrparentid);
				$arrcatdir = array();
				krsort($arrparentid);
				foreach ($arrparentid as $id)
				{
					if ($id==0) continue;
					$arrcatdir[] = $this->categorys[$id]['tagdir'];
					if ($this->categorys[$id]['parentdir'] == '') break;
				}
				krsort($arrcatdir);
				return implode('/', $arrcatdir).'/';
			}
		}
	}

	/**
	* 更新栏目链接地址
	*/
	private function update_url($module,$id)
	{
		$id = intval($id);
		if (!$id || $module) return false;
		$url = Next::loadClass($module.'_class_url'); //调用URL实例
		return $url->category_url($id);
	}

	/**
	 * 获取站点域名
	 * @param $siteid   站点id
	 */
	private function siteurl($siteid = SITEID)
	{
		static $sitelist;
		if(empty($sitelist)) $sitelist = getcache('sitelist','admin');
		return substr($sitelist[$siteid]['url'],0,-1);
	}

	/**
	 * 递归删除栏目
	 * @param $id 要删除的栏目id
	 */
	public function delete_child($id)
	{
		$id = intval($id);
		if (empty($id)) return false;
		$r = admin_model_tag::model()->WHERE(array('parentid'=>$id))->select(1);
		if($r)
		{
			$this->delete_child($r['id']);
			admin_model_tag::model()->WHERE(array('id'=>$r['id']))->select(1);
		}
		return true;
	}
}
?>