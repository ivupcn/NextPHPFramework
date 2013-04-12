<?php
defined('IN_Next') or exit('No permission resources.');
class admin_model_sql extends model {
	public $db_config, $db_setting;
	public function __construct($db_config = array(), $db_setting = '') {
		if (!$db_config) {
			$this->db_config = Next::config('database');
		} else {
			$this->db_config = $db_config;
		}
		if (!$db_setting) {
			$this->db_setting = 'default';
		} else {
			$this->db_setting = $db_setting;
		}
		$this->validations = array();
		parent::__construct();
		if ($db_setting && $db_config[$db_setting]['db_tablepre']) {
			$this->db_tablepre = $db_config[$db_setting]['db_tablepre'];
		}
	}

	static function model()
    {
        static $model;
        if (is_null($model)) $model = new admin_model_sql();
        return $model;
    }

    public function sql_query($sql) {
		if (!empty($this->db_tablepre)) $sql = str_replace('phpcms_', $this->db_tablepre, $sql);
		return parent::query($sql);
	}
	
	public function fetch_next() {
		return $this->db->fetch_next();
	}
}
?>