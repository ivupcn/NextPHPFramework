<?php
defined('IN_Next') or exit('No permission resources.');
class user_model_user extends model
{
	public function __construct()
	{
		$this->db_config = Next::config('database');
		$this->db_setting = 'default';
		$this->table_name = 'user';
		$this->validations = array
		(
			'email' => array
			(
				array('is_email','请输入正确的E-Mail格式，例如：ivup@ivup.cn'),
				array('max_length', 40, 'email不能超过 40 个字符'),
			),
			'password' => array
			(
				array('not_empty','密码不能为空，请输入密码'),
				array('between_length',6,20,'密码长度应大于6位小于20位'),
			),
			'code' => array
			(
				array('not_empty','验证码不能为空,请输入验证码'),
				array('is_code','验证码输入错误'),
			),
		);
		parent::__construct();
	}

    static function model()
    {
        static $model;
        if (is_null($model)) $model = new user_model_user();
        return $model;
    }
}
?>