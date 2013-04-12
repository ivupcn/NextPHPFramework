<?php
/**
 *  acl.php acl 实现了权限检查服务。
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-04
 * 
 * acl 实现了权限检查服务
 *
 * “基于角色”通过比对拥有的角色和访问需要的角色来决定是否通过权限检查。
 *
 * 在进行权限检查时，要求分别提供角色组和访问控制列表（acl）。
 * 然后由 acl 比对角色组和访问控制列表，并返回检查结果。
 *
 * NACL::rolesBasedCheck() 用于比对权限，并返回结果。
 * NACL::normalize() 方法用于将访问控制列表转换为符合规范的访问控制列表。
 */
class NACL
{
    /**
     * 预定义角色常量
     */
    const ACL_EVERYONE    = 'acl_everyone';
    const ACL_NULL        = 'acl_null';
    const ACL_NO_ROLE     = 'acl_no_role';
    const ACL_HAS_ROLE    = 'acl_has_role';
    const ACL_LOCALHOST   = 'acl_localhost';
    const ALL_CONTROLLERS = 'all_controllers';
    const ALL_ACTIONS     = 'all_actions';

    /**
     * 构造函数
     *
     * @context array $app_config
     *
     * 构造应用程序对象
     */
    private function __construct(){}
    

    /**
     * 返回应用程序类的唯一实例
     *
     *
     * @return application
     */
    static function instance()
    {
        static $instance;
        if (is_null($instance))
        {
            $instance = new NACL();
        }
        return $instance;
    }

    /**
     * 检查访问控制表是否允许指定的角色访问
     *
     * 详细使用说明请参考开发者手册“访问控制”章节。
     *
     * @param array $roles 要检查的角色
     * @param array $acl 访问控制表
     * @param boolean $skip_normalize 是否跳过对 ACL 的整理
     *
     * @return boolean 检查结果
     */
    static function rolesBasedCheck($roles, $acl, $skip_normalize = false)
    {
        $roles = array_map('strtolower', normalize($roles));
        if (!$skip_normalize) $acl = self::normalize($acl);
        if ($acl['role'] == self::ACL_EVERYONE)
        {
            return true;
        }

        do {
            // 如果 $acl['role'] 要求用户具有角色，但用户没有角色时直接不通过检查
            if ($acl['role'] == self::ACL_HAS_ROLE)
            {
                if (!empty($roles))
                {
                    break;
                }
                return false;
            }

            // 如果 $acl['role'] 要求用户没有角色，但用户有角色时直接不通过检查
            if ($acl['role'] == self::ACL_NO_ROLE)
            {
                if (empty($roles))
                {
                    break;
                }
                return false;
            }

            // 如果 $acl['role'] 要求用户具有特定角色，则进行检查
            if ($acl['role'] != self::ACL_NULL)
            {
                $passed = false;
                foreach ($roles as $role)
                {
                    if (in_array($role, $acl['role']))
                    {
                        $passed = true;
                        break;
                    }
                }
                if (!$passed)
                {
                    return false;
                }
            }
        } while (false);

        return true;
    }

    /**
     * 对 ACL 整理，返回整理结果
     *
     * @param array $acl 要整理的 ACL
     *
     * @return array
     */
    static function normalize(array $acl)
    {
        $acl = array_change_key_case($acl, CASE_LOWER);
        $ret = array();
        do {
            if (!isset($acl['role']))
            {
                $values = self::ACL_NULL;
                break;
            }

            $acl['role'] = strtolower($acl['role']);
            if ($acl['role'] == self::ACL_EVERYONE || $acl['role'] == self::ACL_HAS_ROLE || $acl['role'] == self::ACL_NO_ROLE || $acl['role'] == self::ACL_NULL)
            {
                $values = $acl['role'];
                break;
            }

            $values = normalize($acl['role']);

            if (empty($values))
            {
                $values = self::ACL_NULL;
            }
        } while (false);
        $ret['role'] = $values;

        return $ret;
    }
}
?>