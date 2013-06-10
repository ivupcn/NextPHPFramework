<?php

class cacheFile {
	
	/*缓存默认配置*/
	protected $_setting = array(
								'suf' => '.cache.php',	/*缓存文件后缀*/
								'type' => 'serialize',		/*缓存格式：array数组，serialize序列化，null字符串*/
							);
	
	/*缓存路径*/
	protected $filepath = '';


	/**
	 * 构造函数
	 * @param	array	$setting	缓存配置
	 * @return  void
	 */
	public function __construct($setting = '') {
		$this->get_setting($setting);
	}
	
	/**
	 * 写入缓存
	 * @param	string	$name		缓存名称
	 * @param	mixed	$data		缓存数据
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed				缓存路径/false
	 */

	public function set($name, $data, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suf'];
	    if(!is_dir($filepath)) {
			mkdir($filepath, 0777, true);
	    }
	    
	    if($this->_setting['type'] == 'array') {
	    	$data = "<?php\nreturn ".var_export($data, true).";\n?>";
	    } elseif($this->_setting['type'] == 'serialize') {
	    	$data = "<?php return array('data' => '".new_addslashes(serialize($data))."');?>";
	    }
	    if ($module == 'common' || ($module == 'common' && substr($name, 0, 16) != 'category_content')) {
		    $db = X::load_model('cache_model', 'admin');
		    $datas = new_addslashes($data);
		    if ($db->get_one(array('filename'=>$filename, 'path'=>'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR), '`filename`')) {
		    	$db->update(array('data'=>$datas), array('filename'=>$filename, 'path'=>'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR));
		    } else {
		    	$db->insert(array('filename'=>$filename, 'path'=>'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR, 'data'=>$datas));
		    }
	    }
	    
	    //是否开启互斥锁
		if(Next::config('system', 'lock_ex', true)) {
			$file_size = file_put_contents($filepath.$filename, $data, LOCK_EX);
		} else {
			$file_size = file_put_contents($filepath.$filename, $data);
		}
	    
	    return $file_size ? $file_size : 'false';
	}
	
	/**
	 * 获取缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  mixed	$data		缓存数据
	 */
	public function get($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suf'];
		if (!file_exists($filepath.$filename)) {
			return false;
		} else {
		    if($this->_setting['type'] == 'array') {
		    	$data = @require($filepath.$filename);
		    } elseif($this->_setting['type'] == 'serialize') {
		    	$arr = require($filepath.$filename);
		    	$data = unserialize(new_stripslashes($arr['data']));
		    }
		    return $data;
		}
	}
	
	/**
	 * 删除缓存
	 * @param	string	$name		缓存名称
	 * @param	array	$setting	缓存配置
	 * @param	string	$type		缓存类型
	 * @param	string	$module		所属模型
	 * @return  bool
	 */
	public function delete($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;	
		$filepath = Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR;
		$filename = $name.$this->_setting['suf'];
		if(file_exists($filepath.$filename)) {
			if ($module == 'commons' && substr($name, 0, 16) != 'category_content') {
				$db = X::load_model('cache_model');
		    	$db->delete(array('filename'=>$filename, 'path'=>'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR));
			}
			return @unlink($filepath.$filename) ? true : false;
		} else {
			return false;
		}
	}
	
	/**
	 * 和系统缓存配置对比获取自定义缓存配置
	 * @param	array	$setting	自定义缓存配置
	 * @return  array	$setting	缓存配置
	 */
	public function get_setting($setting = '') {
		if($setting) {
			$this->_setting = array_merge($this->_setting, $setting);
		}
	}

	public function cacheinfo($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = Next::config('system','cache_path',APP_PATH.'cache'.DIRECTORY_SEPARATOR).'cache_'.$module.DIRECTORY_SEPARATOR.'cache_'.$type.DIRECTORY_SEPARATOR;
		$filename = $filepath.$name.$this->_setting['suf'];
		
		if(file_exists($filename)) {
			$res['filename'] = $name.$this->_setting['suf'];
			$res['filepath'] = $filepath;
			$res['filectime'] = filectime($filename);
			$res['filemtime'] = filemtime($filename);
			$res['filesize'] = filesize($filename);
			return $res;
		} else {
			return false;
		}
	}

}

?>