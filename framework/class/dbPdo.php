<?php
/**
 *  dbPdo.php 数据库实现类
 *
 * @copyright			(C) 2005-2013 ivup.cn
 * @license				http://ivup.cn/license/
 * @lastmodify			2013-5-2
 */

final class dbPdo
{
	/**
	 * 数据库配置信息
	 */
	private $config = null;

	/**
	 * 数据源名称
	 */
	public $dsn = null;

	/**
	 * 数据库连接的属性
	 */
	public $options = array();

	/**
	 * 数据库连接资源句柄
	 */
	public $link = null;

	/**
	 * 最近一次查询资源句柄
	 */
	public $lastqueryid = null;

	/**
	 *  统计数据库查询次数
	 */
	public $querycount = 0;

	public function __construct() {}

	/**
	 * 打开数据库连接,有可能不真实连接数据库
	 * @param $config	数据库连接参数
	 * 			
	 * @return void
	 */
	public function open($config)
	{
		$this->config = $config;
		if($config['autoconnect'] == 1)
		{
			$this->connect();
		}
	}

	/**
	 * 真正开启数据库连接
	 * 			
	 * @return void
	 */
	public function connect()
	{
		if($this->config['driver'] != 'sqlite')
		{
			$this->dsn = $this->config['driver'].':host='.$this->config['hostname'].';port='.$this->config['dbport'].';dbname='.$this->config['database'];
		}
		else
		{
			$this->dsn = $this->config['driver'].':'.$this->config['hostname'].'/'.$this->config['database'].'.db';
		}
		if($this->config['pconnect'] == 1)
		{
			$this->options = array(PDO::ATTR_PERSISTENT => true);
		}
		else
		{
			$this->options = array(PDO::ATTR_PERSISTENT => false);
		}
		if(!$this->link = new PDO($this->dsn, $this->config['username'], $this->config['password'], $this->options))
		{
			NLOG::error('    Can not connect to database server');
		}
		// if($this->config['database'] && !$this->link->exec("use database ".$this->config['database']))
		// {
		// 	NLOG::error('    Cannot use database '.$this->config['database']);
		// }
	}

	/**
	 * 数据库查询执行方法
	 * @param $sql 要执行的sql语句
	 * @return 查询资源句柄
	 */
	private function execute($sql)
	{
		NLOG::log('    REQUEST SQL：' . $sql);
		if(!is_resource($this->link))
		{
			$this->connect();
		}
		$this->lastqueryid = $this->link->prepare($sql);
		$this->lastqueryid->execute();
		$this->querycount++;
		return $this->lastqueryid;
	}

	/**
	 * 执行sql查询
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $table 		数据表
	 * @param $where 		查询条件[例`name`='$name']
	 * @param $limit 		返回结果范围[例：10或10,10 默认为空]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @param $key 			返回数组按键名排序
	 * @return array		查询结果集数组
	 */
	public function select($data, $table, $where = '', $limit = '', $order = '', $group = '', $key = '')
	{
		$where = $where == '' ? '' : ' WHERE '.$where;
		$order = $order == '' ? '' : ' ORDER BY '.$order;
		$group = $group == '' ? '' : ' GROUP BY '.$group;
		$limit = $limit == '' ? '' : ' LIMIT '.$limit;
		$field = explode(',', $data);
		array_walk($field, array($this, 'add_special_char'));
		$data = implode(',', $field);

		$sql = 'SELECT '.$data.' FROM `'.$this->config['database'].'`.`'.$table.'`'.$where.$group.$order.$limit;
		$this->execute($sql);
		$datalist = $this->lastqueryid->fetchAll(PDO::FETCH_ASSOC);
		$this->free_result();
		return $datalist;
	}

	/**
	 * 获取单条记录查询
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $table 		数据表
	 * @param $where 		查询条件
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @return array/null	数据查询结果集,如果不存在，则返回空
	 */
	public function get_one($data, $table, $where = '', $order = '', $group = '')
	{
		$where = $where == '' ? '' : ' WHERE '.$where;
		$order = $order == '' ? '' : ' ORDER BY '.$order;
		$group = $group == '' ? '' : ' GROUP BY '.$group;
		$limit = ' LIMIT 1';
		$field = explode( ',', $data);
		array_walk($field, array($this, 'add_special_char'));
		$data = implode(',', $field);

		$sql = 'SELECT '.$data.' FROM `'.$this->config['database'].'`.`'.$table.'`'.$where.$group.$order.$limit;
		$this->execute($sql);
		$res = $this->lastqueryid->fetch(PDO::FETCH_ASSOC);
		$this->free_result();
		return $res;
	}

	/**
	 * 执行添加记录操作
	 * @param $data 		要增加的数据，参数为数组。数组key为字段值，数组值为数据取值
	 * @param $table 		数据表
	 * @return boolean
	 */
	public function insert($data, $table, $return_insert_id = false, $replace = false)
	{
		if(!is_array( $data ) || $table == '' || count($data) == 0)
		{
			return false;
		}
		
		$fielddata = array_keys($data);
		$valuedata = array_values($data);
		array_walk($fielddata, array($this, 'add_special_char'));
		array_walk($valuedata, array($this, 'escape_string'));
		
		$field = implode (',', $fielddata);
		$value = implode (',', $valuedata);

		$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
		$sql = $cmd.' `'.$this->config['database'].'`.`'.$table.'`('.$field.') VALUES ('.$value.')';
		$return = $this->execute($sql);
		return $return_insert_id ? $this->insert_id() : $return;
	}

	/**
	 * 执行更新记录操作
	 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
	 * 						为数组时数组key为字段值，数组值为数据取值
	 * 						为字符串时[例：`name`='xms',`hits`=`hits`+1]。
	 *						为数组时[例: array('name'=>'xms','password'=>'123456')]
	 *						数组可使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
	 * @param $table 		数据表
	 * @param $where 		更新数据时的条件
	 * @return boolean
	 */
	public function update($data, $table, $where = '')
	{
		if($table == '' or $where == '')
		{
			return false;
		}

		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '')
		{
			$field = $data;
		}
		elseif (is_array($data) && count($data) > 0)
		{
			$fields = array();
			foreach($data as $k=>$v)
			{
				switch (substr($v, 0, 2))
				{
					case '+=':
						$v = substr($v,2);
						if (is_numeric($v))
						{
							$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'+'.$this->escape_string($v, '', false);
						}
						else
						{
							continue;
						}
						
						break;
					case '-=':
						$v = substr($v,2);
						if (is_numeric($v))
						{
							$fields[] = $this->add_special_char($k).'='.$this->add_special_char($k).'-'.$this->escape_string($v, '', false);
						}
						else
						{
							continue;
						}
						break;
					default:
						$fields[] = $this->add_special_char($k).'='.$this->escape_string($v);
				}
			}
			$field = implode(',', $fields);
		}
		else 
		{
			return false;
		}

		$sql = 'UPDATE `'.$this->config['database'].'`.`'.$table.'` SET '.$field.$where;
		return $this->execute($sql);
	}

	/**
	 * 执行删除记录操作
	 * @param $table 		数据表
	 * @param $where 		删除数据条件,不充许为空。
	 * 						如果要清空表，使用empty方法
	 * @return boolean
	 */
	public function delete($table, $where)
	{
		if ($table == '' || $where == '')
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$sql = 'DELETE FROM `'.$this->config['database'].'`.`'.$table.'`'.$where;
		return $this->execute($sql);
	}

	/**
	 * 获取最后一次添加记录的主键号
	 * @return int 
	 */
	public function insert_id()
	{
		return $this->link->lastInsertId();
	}

	/**
	 * 释放查询资源
	 * @return void
	 */
	public function free_result()
	{
		if(is_resource($this->lastqueryid)) {
			$this->lastqueryid = null;
		}
	}

	public function close()
	{
		if (is_resource($this->link))
		{
			$this->link = null;
		}
	}

	/**
	 * 对字段两边加反引号，以保证数据库安全
	 * @param $value 数组值
	 */
	public function add_special_char(&$value)
	{
		if('*' == $value || false !== strpos($value, '(') || false !== strpos($value, '.') || false !== strpos ( $value, '`'))
		{
			//不处理包含* 或者 使用了sql方法。
		}
		else
		{
			$value = '`'.trim($value).'`';
		}
		if(preg_match("/\b(select|insert|update|delete)\b/i", $value))
		{
			$value = preg_replace("/\b(select|insert|update|delete)\b/i", '', $value);
		}
		return $value;
	}

	/**
	 * 对字段值两边加引号，以保证数据库安全
	 * @param $value 数组值
	 * @param $key 数组key
	 * @param $quotation 
	 */
	public function escape_string(&$value, $key='', $quotation = 1)
	{
		if ($quotation)
		{
			$q = '\'';
		}
		else
		{
			$q = '';
		}
		$value = $q.$value.$q;
		return $value;
	}
}
?>