<?php
defined('IN_Next') or exit('No permission resources.');
class content_model_model extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'model';
		$this->validations = array();
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new content_model_model();
        return $model;
    }
}
?>