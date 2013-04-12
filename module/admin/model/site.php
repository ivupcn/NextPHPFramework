<?php
defined('IN_Next') or exit('No permission resources.');
class admin_model_site extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'site';
		$this->validations = array
		(
			'name' => array(
				array('not_empty','站点名称不能为空，请输入站点名称'),
				array('max_length', 30, '菜单名称不能超过 30 个字符'),
			),
			'dirname' => array(
				array('not_empty','站点目录不能为空，请输入站点目录'),
				array('is_alnumu','站点目录仅能为字母、数字加下划线'),
			),
			'domain' => array(
				array('not_empty','站点域名不能为空，请输入站点域名'),
			),
			'setting' => array(
				array('is_array','配置数据错误！'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new admin_model_site();
        return $model;
    }
}
?>