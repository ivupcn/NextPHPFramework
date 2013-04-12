<?php
defined('IN_Next') or exit('No permission resources.');
class user_model_rolepriv extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'user_role_priv';
		$this->validations = array();
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new user_model_rolepriv();
        return $model;
    }
}
?>