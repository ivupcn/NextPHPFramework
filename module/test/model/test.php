<?php
defined('IN_Next') or exit('No permission resources.');
class test_model_test extends modelPdo {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'pdo';
		$this->table_name = 'menu';
		$this->validations = array();
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new test_model_test();
        return $model;
    }
}
?>