<?php
defined('IN_Next') or exit('No permission resources.');
class oa_model_task extends model {
	public function __construct() {
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'task';
		$this->validations = array
		(
			'tagid' => array
			(
				array('not_empty','任务类型不能为空，请选择任务类型'),
				array('is_int','任务类型参数错误'),
			),
			'title' => array(
				array('not_empty','任务名称不能为空，请输入任务名称'),
				array('max_length','255','任务名称长度最大为 255 个字符')
			),
			'planstarttime' => array(
				array('not_empty','计划开始时间不能为空，请选择计划开始时间'),
				array('is_date','计划开始时间参数错误'),
			),
			'planendtime' => array(
				array('not_empty','计划完成时间不能为空，请选择计划完成时间'),
				array('is_date','计划完成时间参数错误'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new oa_model_task();
        return $model;
    }
}
?>