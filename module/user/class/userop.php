<?php
defined('IN_Next') or exit('No permission resources.');

//定义在后台
defined('IN_ADMIN') or define('IN_ADMIN',true);
class user_class_userop
{
	/*
	 * 修改密码
	 */
	public function edit_password($userid, $password){
		$userid = intval($userid);
		if($userid < 1) return false;
		user_model_user::model()->validate(array('password'=>$password),'email,code');
		$passwordinfo = password($password);
		return user_model_user::model()->update($passwordinfo,array('userid'=>$userid));
	}
	/*
	 * 检查email重名
	 */	
	public function checkemail($email) {
		$email =  trim($email);
		if (user_model_user::model()->get_one(array('email'=>$email),'userid')){
			return false;
		}
		return true;
	}

	/**
     * 获取用户信息
     * @param integer $userid   用户ID  
     * @param string $field   字段名  
     * @return array $string
     */
    public function get_userinfo($userid = '', $field = '')
    {
        if($userid)
        {
            $user = user_model_user::model()->get_one(array('userid'=>$userid));
            if($field)
            {
                return $user[$field];
            }
            else
            {
                return $user;
            }
        }
        else
        {
            return false;
        }
    }	
}
?>