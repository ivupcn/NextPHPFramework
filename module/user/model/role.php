<?php
defined('IN_Next') or exit('No permission resources.');
class user_model_role extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'user_role';
		$this->validations = array
		(
			'rolename' => array
			(
				array('not_empty', '角色名不能为空'),
				array('max_length','50','角色名长度最大为 50 个字符'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new user_model_role();
        return $model;
    }
}
?>