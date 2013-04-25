<?php
defined('IN_Next') or exit('No permission resources.');
class content_model_field extends model
{
	public $table_name = '';

	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'model_field';
		$this->validations = array();
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new content_model_field();
        return $model;
    }

    /**
	 * 删除字段
	 * 
	 */
	public function drop_field($tablename,$field)
	{
		$this->table_name = $this->db_tablepre.$tablename;
		$fields = $this->get_fields();
		if(in_array($field, array_keys($fields)))
		{
			return $this->db->query("ALTER TABLE `$this->table_name` DROP `$field`;");
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 改变数据表
	 */
	public function change_table($tablename = '')
	{
		if (!$tablename) return false;
		
		$this->table_name = $this->db_tablepre.$tablename;
		return true;
	}
}
?>