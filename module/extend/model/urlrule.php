<?php
defined('IN_Next') or exit('No permission resources.');
class extend_model_urlrule extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'urlrule';
		$this->validations = array
		(
			'file' => array(
				array('not_empty','URL 规则名称不能为空，请输入 URL 规则名称'),
				array('max_length', 20, 'URL 规则名称不能超过 20 个字符'),
			),
			'example' => array(
				array('not_empty','URL 示例不能为空，请输入 URL 示例'),
				array('max_length', 255, 'URL 示例不能超过 255 个字符'),
			),
			'urlrule' => array(
				array('not_empty','URL 规则不能为空，请输入 URL 规则'),
				array('max_length', 255, 'URL 规则不能超过 255 个字符'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new extend_model_urlrule();
        return $model;
    }
}
?>