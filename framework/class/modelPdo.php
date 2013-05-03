<?php 
class modelPdo
{
	//数据库配置
	protected $db_config = '';
	//数据库连接
	protected $db = '';
	//调用数据库的配置项
	protected $db_setting = 'default';
	//数据库操作实例化列表
	protected $db_list = array();
	//数据表名
	protected $table_name = '';
	//数据表前缀
	protected $db_tablepre = '';
	//数据验证规则
	protected $validations = array();
	//链操作方法列表
    protected $sql_methods = array('field','where','table','join','union','order','limit','alias','having','group','lock','distinct','validate');
    //SQL属性
	protected $sql_options = array();

	public function __construct()
	{
		if(!isset($this->db_config[$this->db_setting]))
		{
			NLOG::error('    Database configuration information can not be empty');
		}
		$this->table_name = $this->db_config[$this->db_setting]['tablepre'].$this->table_name;
		$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->db = $this->get_database($this->db_setting);
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		$this->db->close();
	}

	/**
	 * 获取数据库操作实例
	 * @param $db_name 数据库配置名称
	 */
	public function get_database($db_name)
	{
		if(!isset($this->db_list[$db_name]) || !is_object($this->db_list[$db_name]))
		{
			$this->db_list[$db_name] = $this->connect($db_name);
		}
		return $this->db_list[$db_name];
	}

	/**
	 *  加载数据库驱动
	 * @param $db_name 	数据库配置名称
	 * @return object
	 */
	public function connect($db_name)
	{
		$object = null;
		switch($this->db_config[$db_name]['type'])
		{
			case 'pdo' :
				$object = dbPdo::instance($this->db_config[$db_name], $this->table_name);
				break;
			case 'mysql' :
				$object = dbMysql::instance($this->db_config[$db_name], $this->table_name);
				break;
			default :
				$object = dbPdo::instance($this->db_config[$db_name], $this->table_name);
		}
		$object->open();
		return $object;
	}

	/**
     * 利用__call方法实现一些特殊的Model方法
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
	public function __call($method,$args)
	{
		if(in_array(strtolower($method),$this->sql_methods,true))
		{
            // 连贯操作的实现
            $this->sql_options[strtolower($method)] = $args[0];
            return $this;
        }
        elseif(in_array(strtolower($method),array('count','sum','min','max','avg'),true))
        {
            // 统计查询的实现
            $field =  isset($args[0]) ? $args[0] : '*';
            return $this->getField(strtoupper($method).'('.$field.') AS NextPHP_'.$method);
        }
        elseif(strtolower(substr($method,0,5))=='getby')
        {
            // 根据某个字段获取记录
            $field = parse_name(substr($method,5));
            $where[$field] =  $args[0];
            return $this->where($where)->find();
        }
        elseif(strtolower(substr($method,0,10))=='getfieldby')
        {
            // 根据某个字段获取记录的某个值
            $name   =   parse_name(substr($method,10));
            $where[$name] =$args[0];
            return $this->where($where)->getField($args[1]);
        }
        else
        {
        	NLOG::error('    sql methond not exist');
            return;
        }
	}

	/**
	 * 查询所有数据
	 * @return array|object		查询结果集数组
	 */
	public function getAll()
	{
		$sql = $this->db->buildSql($this->sql_options);
		$this->db->execute($sql);
		$datalist = $this->db->lastqueryid->fetchAll(PDO::FETCH_ASSOC);
		$this->sql_options = array();
		$this->db->free_result();
		return $datalist;
	}

	/**
	 * 查询一条数据
	 * @return array|object		查询结果集数组
	 */
	public function getOne()
	{
		$sql = $this->db->buildSql($this->sql_options);
		$this->db->execute($sql);
		$datalist = $this->db->lastqueryid->fetch(PDO::FETCH_ASSOC);
		$this->sql_options = array();
		$this->db->free_result();
		return $datalist;
	}

	/**
	 * 查询多条数据并分页
	 * @return array|object		查询结果集数组
	 */
	public function getList()
	{
		$sql = $this->db->buildSql($this->sql_options);
		$this->db->execute($sql);
		$datalist = $this->db->lastqueryid->fetchAll(PDO::FETCH_ASSOC);
		$this->sql_options = array();
		$this->db->free_result();
		return $datalist;
	}

	/**
     * 获取一条记录的某个字段值
     * @param string $field  字段名
     * @param string $spea  字段数据间隔符号 NULL返回数组
     * @return mixed
     */
    public function getField($field,$sepa=null)
    {
        $this->sql_options['field'] = $field;
        $field = trim($field);
        if(strpos($field,','))
        { // 多字段
            if(!isset($this->sql_options['limit']))
            {
                $this->sql_options['limit'] = is_numeric($sepa) ? $sepa : '';
            }
            $resultSet = $this->db->select($options);
            if(!empty($resultSet))
            {
                $_field = explode(',', $field);
                $field =  array_keys($resultSet[0]);
                $key = array_shift($field);
                $key2 = array_shift($field);
                $cols = array();
                $count = count($_field);
                foreach($resultSet as $result)
                {
                    $name = $result[$key];
                    if(2 == $count)
                    {
                        $cols[$name] = $result[$key2];
                    }
                    else
                    {
                        $cols[$name] = is_string($sepa) ? implode($sepa,$result) : $result;
                    }
                }
                return $cols;
            }
        }
        else
        {   // 查找一条记录
            // 返回数据个数
            if(true !== $sepa)
            {// 当sepa指定为true的时候 返回所有数据
                $this->sql_options['limit'] = is_numeric($sepa) ? $sepa : 1;
            }
            $result = $this->db->select($options);
            if(!empty($result))
            {
                if(true !== $sepa && 1 == $this->sql_options['limit']) return reset($result[0]);
                foreach($result as $val)
                {
                    $array[] = $val[$field];
                }
                return $array;
            }
        }
        return null;
    }
}
?>