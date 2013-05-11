<?php
defined('IN_Next') or exit('No permission resources.');
class content_model_model
{
	static function __define()
	{
		return array(
			// 数据库配置
			'db_config' => Next::config('database','default'),
			// 数据表
			'table_name' => 'model',
			// 指定在数据库中创建对象时，哪些属性的值不允许由外部提供，这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值。
			'insert_reject' => 'modelid',
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
					array('not_empty','模型名称不能为空，请输入模型名称'),
					array('max_length', 30, '模型名称不能超过 30 个字符'),
				),
				'tablename' => array(
					array('not_empty','模型表键名不能为空，请输入模型表键名'),
					array('is_alnumu','模型表键名只能为字母、数字加下划线'),
					array('max_length', 20, '模型表键名称不能超过 20 个字符'),
				)
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


    static function sql_execute($sql)
    {
		$sqls = self::sql_split($sql);
		if(is_array($sqls)) {
			foreach($sqls as $sql)
			{
				if(trim($sql) != '')
				{
					db::getInstance(__CLASS__)->exec($sql);
				}
			}
		}
		else
		{
			db::getInstance(__CLASS__)->exec($sqls);
		}
		return true;
	}

	static function sql_split($sql)
	{
		$config = Next::config('database','default');
		if($config['charset'])
		{
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$config['charset'],$sql);
		}
		if($config['tablepre'] != "x_") $sql = str_replace("x_", $config['tablepre'], $sql);
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query)
		{
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query)
			{
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}

	/**
	 * 删除表
	 * 
	 */
	static function drop_table($tablename)
	{
		$config = Next::config('database','default');
		$tablepre = $config['tablepre'];
		$tablename = $tablepre.'post_'.SITEID.'_'.$tablename;
		$tablelist = db::getInstance(__CLASS__)->SQL('SHOW TABLES')->query();
		$tablearr = array();
		foreach($tablelist as $tables)
		{
			$tablearr[] = $tables['Tables_in_'.$config['database']];
		}
		if(in_array($tablename, $tablearr))
		{
			return db::getInstance(__CLASS__)->SQL('DROP TABLE '.$tablename)->query();
		}
		else
		{
			return false;
		}
	}

	/*
	 * 检查表是否存在
	 */
	static function table_exists($table)
	{
		$config = Next::config('database','default');
		$tablelist = db::getInstance(__CLASS__)->SQL('SHOW TABLES')->query();
		$tablearr = array();
		foreach($tablelist as $tables)
		{
			$tablearr[] = $tables['Tables_in_'.$config['database']];
		}
		if(in_array($config['tablepre'].$table, $tablearr))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>