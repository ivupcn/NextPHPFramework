<?php
defined('IN_Next') or exit('No permission resources.');
class content_model_model extends model {
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
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new content_model_model();
        return $model;
    }
}
?>