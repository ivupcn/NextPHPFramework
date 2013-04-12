<?php
defined('IN_Next') or exit('No permission resources.');
class extend_model_workcheck extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'workcheck';
		$this->validations = array();
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new extend_model_workcheck();
        return $model;
    }
}
?>