<?php
defined('IN_Next') or exit('No permission resources.');
class admin_model_tag extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'tag';
		$this->validations = array();
		parent::__construct();
	}

	static function model()
    {
        static $model;
        if (is_null($model)) $model = new admin_model_tag();
        return $model;
    }
}
?>