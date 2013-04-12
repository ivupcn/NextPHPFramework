<?php
X::load_sys_func('dir');
class admin_class_module
{
	private $m_db, $installdir, $uninstaldir, $module, $isall;
	public $error_msg = '';

	public function install($module = '')
	{
		defined('INSTALL') or exit('Undefined module install');
		if ($module) $this->module = $module;
		$this->installdir = Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$this->module.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR;
		$models = @require($this->installdir.'model.php');
		if(!is_array($models) || empty($models))
		{
			$models = array('module');
		}
		if(!in_array('module', $models))
		{
			array_unshift($models, 'module');
		}
		if(is_array($models) && !empty($models))
		{
			foreach ($models as $m)
			{
				//$this->m_db = new $this->module.'_model_'.$m();
				$sql = file_get_contents($this->installdir.$m.'.sql');
				$this->sql_execute($sql);
			}
		}
		if(file_exists($this->installdir.'extention.php')) 
		{
			$menu_db = admin_model_menu::model();
			@include ($this->installdir.'extention.php');
		}
		return true;
	}

	/**
	 * 检查安装目录
	 * @param string $module 模块名
	 */
	public function check($module = '')
	{
		define('INSTALL',true);
		if ($module) $this->module = $module;
		if(!$this->module)
		{
			$this->error_msg = '请先确认要安装的模块！';
			return false;
		}
		$r = admin_model_module::model()->get_one(array('module'=>$this->module));
		if($r)
		{
			$this->error_msg = '系统内已存在该模块，请先卸载后再执行该安装程序！';
			return false;
		}
		if(!$this->installdir)
		{
			$this->installdir = Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$this->module.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR;
		}
		if(!is_dir($this->installdir))
		{
			$this->error_msg = '安装程序目录不存在，请检查！';
			return false;
		}
		if(!file_exists($this->installdir.'module.sql'))
		{
			$this->error_msg = 'module.sql不存在，请检查该文件是否在安装目录';
			return false;
		}
		if(!file_exists($this->installdir.'model.php'))
		{
			$this->error_msg = 'model.php不存在，请检查该文件是否在安装目录';
			return false;
		}
		else
		{
			$models = @require($this->installdir.'model.php');
			if(is_array($models) && !empty($models))
			{
				foreach ($models as $m)
				{
					if(!file_exists(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$this->module.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.$m.'.php'))
					{
						$this->error_msg = $m.'model.php文件不存在，请先将该文件上传至数据模型目录！';
						return false;
					}
					if(!file_exists($this->installdir.$m.'.sql'))
					{
						$this->error_msg = $m.'.sql 不存在，请检查该文件是否在安装目录';
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 * 执行mysql.sql文件，创建数据表等
	 * @param string $sql sql语句
	 */
	private function sql_execute($sql)
	{
	    $sqls = $this->sql_split($sql);

		if(is_array($sqls))
		{
			foreach ($sqls as $sql)
			{
				if(trim($sql) != '')
				{
					admin_model_module::model()->query($sql);
				}
			}
		}
		else
		{
			admin_model_module::model()->query($sqls);
		}
		return true;
	}
	
	/**
	 * 处理sql语句，执行替换前缀都功能。
	 * @param string $sql 原始的sql，将一些大众的部分替换成私有的
	 */
	private function sql_split($sql)
	{
		$dbcharset = X::load_config('database','default');
		$dbcharset = $dbcharset['charset'];
		if(admin_model_module::model()->version() > '4.1' && $dbcharset)
		{
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$dbcharset, $sql);
		}
		if(admin_model_module::model()->db_tablepre != "x_") $sql = str_replace("x_", $this->m_db->db_tablepre, $sql);
		$sql = str_replace(array("\r", '2010-9-05'), array("\n", date('Y-m-d')), $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach ($queriesarray as $query)
		{
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach ($queries as $query)
			{
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return $ret;
	}
}
?>