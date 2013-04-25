<?php
defined('IN_Next') or exit('No permission resources.');
class content_model_model extends model
{
	public $table_name = '';

	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'model';
		$this->validations = array(
			'name' => array(
				array('not_empty','模型名称不能为空，请输入模型名称'),
				array('max_length', 30, '模型名称不能超过 30 个字符'),
			),
			'tablename' => array(
				array('not_empty','模型表键名不能为空，请输入模型表键名'),
				array('is_alnumu','模型表键名只能为字母、数字加下划线'),
				array('max_length', 20, '模型表键名称不能超过 20 个字符'),
			),
		);
		parent::__construct();
		$this->charset = $this->db_config[$this->db_setting]['charset'];
		$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new content_model_model();
        return $model;
    }

    public function sql_execute($sql)
    {
		$sqls = $this->sql_split($sql);
		if(is_array($sqls)) {
			foreach($sqls as $sql)
			{
				if(trim($sql) != '')
				{
					$this->db->query($sql);
				}
			}
		}
		else
		{
			$this->db->query($sqls);
		}
		return true;
	}

	public function sql_split($sql)
	{
		global $db;
		if($this->db->version() > '4.1' && $this->charset)
		{
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$this->charset,$sql);
		}
		if($this->db_tablepre != "x_") $sql = str_replace("x_", $this->db_tablepre, $sql);
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query)
		{
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query)
			{
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}

	/**
	 * 删除表
	 * 
	 */
	public function drop_table($tablename)
	{
		$tablename = $this->db_tablepre.$tablename;
		$tablearr = $this->db->list_tables();
		if(in_array($tablename, $tablearr)) {
			return $this->db->query("DROP TABLE $tablename");
		} else {
			return false;
		}
	}
}
?>