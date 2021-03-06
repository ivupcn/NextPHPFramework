<?php
defined('IN_Next') or exit('No permission resources.');
class admin_model_menu
{
	static function __define()
	{
		return array(
			// 数据库配置
			'db_config' => Next::config('database','default'),
			// 数据表
			'table_name' => 'menu',
			// 指定在数据库中创建对象时，哪些属性的值不允许由外部提供，这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值。
			'insert_reject' => 'id',
			// 指定在数据库中更新对象时，哪些属性的值不允许由外部提供，这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值。
			'update_reject' => '',
			/* 指定在数据库中创建对象时，哪些属性的值由下面指定的内容进行覆盖
			 * 如果填充值为 self::AUTOFILL_TIMESTAMP 或 self::AUTOFILL_DATETIME，则会根据属性的类型来自动填充当前时间（整数或字符串）。
			 * 如果填充值为一个数组，则假定为 callback 方法。
			 */
			'insert_autofill' => array(),
			/* 指定在数据库中更新对象时，哪些属性的值由下面指定的内容进行覆盖
			 * 如果填充值为 self::AUTOFILL_TIMESTAMP 或 self::AUTOFILL_DATETIME，则会根据属性的类型来自动填充当前时间（整数或字符串）。
			 * 如果填充值为一个数组，则假定为 callback 方法。
			 */
			'update_autofill' => array(),
			/**
             * 在保存对象时，会按照下面指定的验证规则进行验证。验证失败会抛出异常。
             *
             * 除了在保存时自动验证，还可以通过对象的 validator::validate() 方法对数组数据进行验证。
             *
             * 如果需要添加一个自定义验证，应该写成
             *
             * 'title' => array(
             *        array(array(__CLASS__, 'checkTitle'), '标题不能为空'),
             * )
             *
             * 然后在该类中添加 checkTitle() 方法。函数原型如下：
             *
             * static function checkTitle($title)
             *
             * 该方法返回 true 表示通过验证。
             */
			'validations' => array(
				'name' => array(
					array('not_empty','菜单名称不能为空，请输入菜单名称'),
					array('max_length', 40, '菜单名称不能超过 40 个字符')
				),
				'm' => array(
					array('not_empty','模块名不能为空，请输入模块名'),
					array('is_alnumu','模块名只能为字母、数字加下划线')
				),
				'c' => array(
					array('not_empty','文件名不能为空，请输入文件名'),
					array('is_alnumu','文件名只能为字母、数字加下划线')
				),
				'a' => array(
					array('not_empty','方法名不能为空，请输入方法名'),
					array('is_alnumu','方法名只能为字母、数字加下划线')
				),
				'data' => array(
					array('max_length','100','参数长度最大为 100 个字符')
				),
			)
		);
	}

	/**
     * 返回当前 model 类的元数据对象
     *
     * @static
     *
     * @return model
     */
    static function model()
    {
        return db::getInstance(__CLASS__);
    }
}
?>