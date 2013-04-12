<?php
defined('IN_Next') or exit('No permission resources.');
class extend_model_workflow extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'workflow';
		$this->validations = array
		(
			'workname' => array(
				array('not_empty','工作流名称不能为空，请输入工作流名称'),
				array('max_length', 20, '工作流名称不能超过 20 个字符'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new extend_model_workflow();
        return $model;
    }
}
?>