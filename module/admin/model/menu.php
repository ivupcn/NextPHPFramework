<?php
defined('IN_Next') or exit('No permission resources.');
class admin_model_menu extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'menu';
		$this->validations = array(
			'name' => array(
				array('not_empty','菜单名称不能为空，请输入菜单名称'),
				array('max_length', 40, '菜单名称不能超过 40 个字符'),
			),
			'm' => array(
				array('not_empty','模块名不能为空，请输入模块名'),
				array('is_alnumu','模块名只能为字母、数字加下划线'),
			),
			'c' => array(
				array('not_empty','文件名不能为空，请输入文件名'),
				array('is_alnumu','文件名只能为字母、数字加下划线'),
			),
			'a' => array(
				array('not_empty','方法名不能为空，请输入方法名'),
				array('is_alnumu','方法名只能为字母、数字加下划线'),
			),
			'data' => array(
				array('max_length','100','参数长度最大为 100 个字符'),
			),
		);
		parent::__construct();
	}

	static function model()
    {
        static $model;
        if (is_null($model)) $model = new admin_model_menu();
        return $model;
    }
}
?>