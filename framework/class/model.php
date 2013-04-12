<?php 
class model {
	
	//数据库配置
	protected $db_config = '';
	//数据库连接
	protected $db = '';
	//调用数据库的配置项
	protected $db_setting = 'default';
	//数据表名
	protected $table_name = '';
	//表前缀
	public $db_tablepre = '';
	//数据验证规则
	protected $validations = array();


	public function __construct()
	{
		if(!isset($this->db_config[$this->db_setting]))
		{
			$this->db_setting = 'default';
		}
		$this->table_name = $this->db_config[$this->db_setting]['tablepre'].$this->table_name;
		$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->db = dbFactory::get_instance($this->db_config)->get_database($this->db_setting);
	}
		
	/**
	 * 执行sql查询
	 * @param $where 		查询条件[例`name`='$name']
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $limit 		返回结果范围[例：10或10,10 默认为空]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @param $key          返回数组按键名排序
	 * @return array		查询结果集数组
	 */
	final public function select($where = '', $data = '*', $limit = '', $order = '', $group = '', $key='') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->select($data, $this->table_name, $where, $limit, $order, $group, $key);
	}

	/**
	 * 查询多条数据并分页
	 * @param $where
	 * @param $order
	 * @param $page
	 * @param $pagesize
	 * @return unknown_type
	 */
	final public function listinfo($where = '', $order = '', $page = 1, $pagesize = 20, $key='', $setpages = 10, $urlrule = '',$array = array(),$pagetype = 1) {
		$where = $this->to_sqls($where);
		$this->number = $this->count($where);
		$page = max(intval($page), 1);
		$offset = $pagesize*($page-1);
		if($pagetype)
		{
			$this->pages = '<div class="pages"><span>共'.$this->number.'条</span></div><div class="pagination" targetType="navTab" totalCount="'.$this->number.'" numPerPage="'. $pagesize.'" pageNumShown="10" currentPage="'.$page.'"></div>';
		}
		else
		{
			$this->pages = pages($this->number, $page, $pagesize, $urlrule, $array, $setpages);
		}
		$array = array();
		if ($this->number > 0) {
			return $this->select($where, '*', "$offset, $pagesize", $order, '', $key);
		} else {
			return array();
		}
	}

	/**
	 * 获取单条记录查询
	 * @param $where 		查询条件
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @return array/null	数据查询结果集,如果不存在，则返回空
	 */
	final public function get_one($where = '', $data = '*', $order = '', $group = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->get_one($data, $this->table_name, $where, $order, $group);
	}
	
	/**
	 * 直接执行sql查询
	 * @param $sql							查询sql语句
	 * @param array $params                 占位符对应的参数值
	 * @return	boolean/query resource		如果为查询语句，返回资源句柄，否则返回true/false
	 */
	final public function query($sql, array $params = null) {
		$sql = str_replace('x_', $this->db_tablepre, $sql);
		if(strpos($sql, '?') == true)
		{
			$parts = explode('?', $sql);
			$str = $parts[0];
	        $offset = 1;
	        foreach ($params as $arg_value)
	        {
	          if (! isset($parts[$offset]))
	            {
	                break;
	            }
	            $str .= safe_replace($arg_value) . $parts[$offset];
	            $offset ++;
	        }
	        $this->db->query($str);
		}
		else
		{
			$this->db->query($sql);
		}
		return $this;
	}
	
	/**
	 * 执行添加记录操作
	 * @param $data 		要增加的数据，参数为数组。数组key为字段值，数组值为数据取值
	 * @param $return_insert_id 是否返回新建ID号
	 * @param $replace 是否采用 replace into的方式添加数据
	 * @return boolean
	 */
	final public function insert($data, $return_insert_id = false, $replace = false) {
		return $this->db->insert($data, $this->table_name, $return_insert_id, $replace);
	}
	
	/**
	 * 获取最后一次添加记录的主键号
	 * @return int 
	 */
	final public function insert_id() {
		return $this->db->insert_id();
	}
	
	/**
	 * 执行更新记录操作
	 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
	 * 						为数组时数组key为字段值，数组值为数据取值
	 * 						为字符串时[例：`name`='xms',`hits`=`hits`+1]。
	 *						为数组时[例: array('name'=>'xms','password'=>'123456')]
	 *						数组的另一种使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
	 * @param $where 		更新数据时的条件,可为数组或字符串
	 * @return boolean
	 */
	final public function update($data, $where = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->update($data, $this->table_name, $where);
	}
	
	/**
	 * 执行删除记录操作
	 * @param $where 		删除数据条件,不充许为空。
	 * @return boolean
	 */
	final public function delete($where) {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->delete($this->table_name, $where);
	}
	
	/**
	 * 计算记录数
	 * @param string/array $where 查询条件
	 */
	final public function count($where = '') {
		$r = $this->get_one($where, "COUNT(*) AS num");
		return $r['num'];
	}
	
	/**
	 * 将数组转换为SQL语句
	 * @param array $where 要生成的数组
	 * @param string $font 连接串。
	 */
	final public function sqls($where, $font = ' AND ') {
		if (is_array($where)) {
			$sql = '';
			foreach ($where as $key=>$val) {
				$sql .= $sql ? " $font `$key` = '$val' " : " `$key` = '$val'";
			}
			return $sql;
		} else {
			return $where;
		}
	}
	
	/**
	 * 获取最后数据库操作影响到的条数
	 * @return int
	 */
	final public function affected_rows() {
		return $this->db->affected_rows();
	}
	
	/**
	 * 获取数据表主键
	 * @return array
	 */
	final public function get_primary() {
		return $this->db->get_primary($this->table_name);
	}
	
	/**
	 * 获取表字段
	 * @param string $table_name    表名
	 * @return array
	 */
	final public function get_fields($table_name = '') {
		if (empty($table_name)) {
			$table_name = $this->table_name;
		} else {
			$table_name = $this->db_tablepre.$table_name;
		}
		return $this->db->get_fields($table_name);
	}
	
	/**
	 * 检查表是否存在
	 * @param $table 表名
	 * @return boolean
	 */
	final public function table_exists($table){
		return $this->db->table_exists($this->db_tablepre.$table);
	}
	
	/**
	 * 检查字段是否存在
	 * @param $field 字段名
	 * @return boolean
	 */
	public function field_exists($field) {
		$fields = $this->db->get_fields($this->table_name);
		return array_key_exists($field, $fields);
	}
	
	/**
	 * 列出表
	 * @return boolean
	 */
	final public function list_tables() {
		return $this->db->list_tables();
	}

	/**
	 * 返回数据结果集
	 * @param $query （mysql_query返回值）
	 * @return array
	 */
	final public function fetch_array() {
		$data = array();
		while($r = $this->db->fetch_next()) {
			$data[] = $r;		
		}
		return $data;
	}

	/**
	 * 返回数据结果集
	 * @param $query （mysql_query返回值）
	 * @return array
	 */
	final public function queryall()
	{
		$data = array();
		while($r = $this->db->fetch_next()) {
			$data[] = $r;		
		}
		return $data;
	}
	
	/**
	 * 返回数据结果集
	 * @param $query （mysql_query返回值）
	 * @return array
	 */
	final public function queryone()
	{
		$data = $this->db->fetch_next();
		return $data;
	}

	/**
	 * 返回数据库版本号
	 */
	final public function version() {
		return $this->db->version();
	}

	/**
	 * 生成sql语句，如果传入$in_cloumn 生成格式为 IN('a', 'b', 'c')
	 * @param $data 条件数组或者字符串
	 * @param $front 连接符
	 * @param $in_column 字段名称
	 * @return string
	 */
	final public function to_sqls($data, $front = ' AND ', $in_column = false)
	{
		if($in_column && is_array($data))
		{
			$ids = '\''.implode('\',\'', $data).'\'';
			$sql = "$in_column IN ($ids)";
			return $sql;
		}
		else
		{
			if ($front == '')
			{
				$front = ' AND ';
			}
			if(is_array($data) && count($data) > 0)
			{
				$sql = '';
				foreach ($data as $key => $val)
				{
					$sql .= $sql ? " $front `$key` = '$val' " : " `$key` = '$val' ";
				}
				return $sql;
			}
			else
			{
				return $data;
			}
		}
	}

    /**
     * 对数据进行验证，返回所有未通过验证数据的错误信息
     *
     * @param array $data 要验证的数据
     * @param steing $reject 不需要验证的数据,多个字段属性用','分隔
     * @return array 所有没有通过验证的属性名称及验证规则
     */
    final function validate(array $data, $reject = null)
    {
    	if($reject != null)
        {
        	$reject_arr = normalize($reject,',');
        	foreach($reject_arr as $rk => $rv)
        	{
        		unset($this->validations[$rv]);
        	}
        }
    	$error = '';
    	foreach ($this->validations as $prop => $policy)
    	{
    		if (!isset($data[$prop]))
            {
                $data[$prop] = null;
            }
            foreach($policy as $index => $rule)
            {
            	$validation = array_shift($rule);
            	$msg = array_pop($rule);
            	array_unshift($rule, $data[$prop]);

            	$ret = validator::validateByArgs($validation, $rule);
            	if ($ret === validator::SKIP_OTHERS)
                {
                    break;
                }
                elseif (!$ret)
                {
                    $error .= $msg.'<br />';
                }
            }
    	}
		if(empty($error))
		{
			return true;
		}
		else
		{
			return application::instance()->showmessage('300',$error);
		}
    }
}
?>