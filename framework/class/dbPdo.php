<?php 
class dbPdo
{
	//当前数据库类静态实例
    private static $db_factory;
	//数据库配置
	protected $config = array();
    //DSN
    protected $dsn = null;
	//数据库连接的属性
	protected $db_options = array();
	//数据库连接资源句柄
	protected $link = null;
	//最近一次查询资源句柄
	public $lastqueryid = null;
	//统计数据库查询次数
	protected $querycount = 0;
    //数据表名
    protected $table_name = '';
    // 数据库表达式
    protected $comparison = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN');

	public function __construct() {}

    /**
     * 返回当前终级类对象的实例
     * @param $db_config 数据库配置
     * @return object
     */
    static function instance($config, $table_name)
    {
        if (is_null(self::$db_factory)) self::$db_factory = new dbPdo();
        self::$db_factory->config = $config;
        self::$db_factory->table_name = $table_name;
        return self::$db_factory;
    }

	/**
	 * 打开数据库连接,有可能不真实连接数据库
	 * @param $config	数据库连接参数		
	 * @return void
	 */
	public function open()
	{
		$this->connect();
	}

	/**
	 * 真正开启数据库连接	
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
			$this->db_options = array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "'.$this->config['charset'].'"');
		}
		else
		{
			$this->db_options = array(PDO::ATTR_PERSISTENT => false, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "'.$this->config['charset'].'"');
		}
		if(!$this->link = new PDO($this->dsn, $this->config['username'], $this->config['password'], $this->db_options))
		{
			NLOG::error('    Can not connect to database server');
		}
	}

	/**
	 * 数据库查询执行方法
	 * @param $sql 要执行的sql语句
	 * @return 查询资源句柄
	 */
	public function execute($sql)
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
	 * 获取最后一次添加记录的主键号
	 * @return int 
	 */
	private function insert_id()
	{
		return $this->link->lastInsertId();
	}

    /**
     * 释放查询资源
     * @return void
     */
    public function free_result()
    {
        if(is_resource($this->lastqueryid))
        {
            $this->lastqueryid = null;
        }
    }

    /**
     * 关闭数据库连接
     * @return void
     */
    public function close()
    {
        if (is_resource($this->link))
        {
            $this->link = null;
        }
    }

	/**
     * 生成SQL语句
     * @param $options array    表达式
     * @return string           
     */
	public function buildSql($options=array())
	{
		$sql = str_replace(
			array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),
			array(
                $this->parseTable(!empty($options['table']) ? $options['table'] : $this->table_name),
                $this->parseDistinct(!empty($options['distinct']) ? $options['distinct'] : false),
                $this->parseField(!empty($options['field']) ? $options['field'] : '*'),
                $this->parseJoin(!empty($options['join']) ? $options['join'] : ''),
                $this->parseWhere(!empty($options['where']) ? $options['where'] : ''),
                $this->parseGroup(!empty($options['group']) ? $options['group'] : ''),
                $this->parseHaving(!empty($options['having']) ? $options['having'] : ''),
                $this->parseOrder(!empty($options['order']) ? $options['order'] : ''),
                $this->parseLimit(!empty($options['limit']) ? $options['limit'] : ''),
                $this->parseUnion(!empty($options['union']) ? $options['union'] : ''),
                $this->parseComment(!empty($options['comment']) ? $options['comment'] : '')
            ),
            'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%'
		);
		return $sql;
	}

	/*
	 * 分析表
	 * @param $table mixed
	 * @return string 
	 */
	private function parseTable($tables)
	{
		if(is_array($tables))
		{
			// 支持 'table1'=>'table2' 这样的数据表别名定义
			$array = array();
			foreach($tables as $table => $alias)
			{
				if(!is_numeric($table))
				{
					$array[] =  $table.' '.$alias;
				}
				else
				{
					$array[] =  $table;
				}
			}
			return implode(',',$array);
		}
		elseif(is_string($tables))
		{
			return $tables;
		}
	}

	/**
     * distinct分析
     * @param $distinct mixed
     * @return string
     */
    private function parseDistinct($distinct)
    {
        return !empty($distinct) ? ' DISTINCT ' : '';
    }

    /**
     * field分析
     * @param $fields mixed
     * @return string
     */
    private function parseField($fields)
    {
        if(is_string($fields) && strpos($fields,','))
        {
            $fields = explode(',',$fields);
        }
        if(is_array($fields))
        {
            // 完善数组方式传字段名的支持
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $array = array();
            foreach($fields as $key=>$field)
            {
                if(!is_numeric($key))
                    $array[] =  $key.' AS '.$field;
                else
                    $array[] =  $field;
            }
            $fieldsStr = implode(',', $array);
        }
        elseif(is_string($fields) && !empty($fields))
        {
            $fieldsStr = $fields;
        }
        else
        {
            $fieldsStr = '*';
        }
        //TODO 如果是查询全部字段，并且是join的方式，那么就把要查的表加个别名，以免字段被覆盖
        return $fieldsStr;
    }

    /**
     * join分析
     * @param $join mixed
     * @return string
     */
    private function parseJoin($join)
    {
    	$joinStr = '';
        if(!empty($join))
        {
            if(is_array($join))
            {
                foreach ($join as $key=>$_join)
                {
                    if(false !== stripos($_join,'JOIN'))
                    {
                        $joinStr .= ' '.$_join;
                    }
                    else
                    {
                        $joinStr .= ' LEFT JOIN ' .$_join;
                    }
                }
            }
            else
            {
                $joinStr .= ' LEFT JOIN ' .$join;
            }
        }
        return $joinStr;
    }

    /**
     * where分析
     * @param $where mixed
     * @return string
     */
    private function parseWhere($where)
    {
        $whereStr = '';
        if(is_string($where))
        {
            // 直接使用字符串条件
            $whereStr = $where;
        }
        else
        { 
            // 使用数组表达式
            foreach ($where as $key=>$val)
            {
                // 查询字段的安全过滤
                if(!preg_match('/^[A-Z_\|\&\-.a-z0-9\(\)\,]+$/',trim($key)))
                {
                    NLOG::error('    Expression errors : '.$key);
                }
                if(is_array($val))
                {
                    if(is_string($val[0]))
                    {
                        if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT)$/i',$val[0]))
                        { // 比较运算
                            $whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
                        }
                        elseif(preg_match('/^(NOTLIKE|LIKE)$/i',$val[0]))
                        {// 模糊查找
                            if(is_array($val[1]))
                            {
                                $likeLogic  =   isset($val[2]) ? strtoupper($val[2]):'OR';
                                if(in_array($likeLogic,array('AND','OR','XOR')))
                                {
                                    $likeStr    =   $this->comparison[strtolower($val[0])];
                                    $like       =   array();
                                    foreach ($val[1] as $item)
                                    {
                                        $like[] = $key.' '.$likeStr.' '.$this->parseValue($item);
                                    }
                                    $whereStr .= '('.implode(' '.$likeLogic.' ',$like).')';                          
                                }
                            }
                            else
                            {
                                if(isset($val[2]) && in_array($val[2], array('%')))
                                {
                                    $whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue('%'.$val[1].'%');
                                }
                                else
                                {
                                    $whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
                                }
                            }
                        }
                        elseif('exp'==strtolower($val[0]))
                        { // 使用表达式
                            $whereStr .= ' ('.$key.' '.$val[1].') ';
                        }
                        elseif(preg_match('/IN/i',$val[0]))
                        { // IN 运算
                            if(isset($val[2]) && 'exp'==$val[2])
                            {
                                $whereStr .= $key.' '.strtoupper($val[0]).' '.$val[1];
                            }
                            else
                            {
                                if(is_string($val[1]))
                                {
                                     $val[1] =  explode(',',$val[1]);
                                }
                                $zone      =   implode(',',$this->parseValue($val[1]));
                                $whereStr .= $key.' '.strtoupper($val[0]).' ('.$zone.')';
                            }
                        }
                        elseif(preg_match('/BETWEEN/i',$val[0]))
                        { // BETWEEN运算
                            $data = is_string($val[1])? explode(',',$val[1]):$val[1];
                            $whereStr .=  ' ('.$key.' '.strtoupper($val[0]).' '.$this->parseValue($data[0]).' AND '.$this->parseValue($data[1]).' )';
                        }
                        else
                        {
                            NLOG::error('    Expression errors : '.$val[0]);
                        }
                    }
                    else
                    {
                        $count = count($val);
                        $rule  = isset($val[$count-1])?strtoupper($val[$count-1]):'';
                        if(in_array($rule,array('AND','OR','XOR')))
                        {
                            $count  = $count -1;
                        }
                        else
                        {
                            $rule   = 'AND';
                        }
                        for($i=0;$i<$count;$i++)
                        {
                            $data = is_array($val[$i])?$val[$i][1]:$val[$i];
                            if('exp'==strtolower($val[$i][0]))
                            {
                                $whereStr .= '('.$key.' '.$data.') '.$rule.' ';
                            }
                            else
                            {
                                $op = is_array($val[$i])?$this->comparison[strtolower($val[$i][0])]:'=';
                                $whereStr .= '('.$key.' '.$op.' '.$this->parseValue($data).') '.$rule.' ';
                            }
                        }
                        $whereStr = substr($whereStr,0,-4);
                    }
                }
                else
                {
                    //对字符串类型字段采用模糊匹配
                    $whereStr .= $whereStr ?  ' AND '.$key.' = '.$this->parseValue($val) : $key.' = '.$this->parseValue($val);
                }
            }
        }
        return empty($whereStr) ? '' : ' WHERE '.$whereStr;
    }

    /**
     * group分析
     * @param $group mixed
     * @return string
     */
    private function parseGroup($group)
    {
        return !empty($group) ? ' GROUP BY '.$group : '';
    }

    /**
     * having分析
     * @param $having string
     * @return string
     */
    private function parseHaving($having)
    {
        return !empty($having) ? ' HAVING '.$having : '';
    }

    /**
     * order分析
     * @param $order mixed
     * @return string
     */
    private function parseOrder($order)
    {
        if(is_array($order))
        {
            $array   =  array();
            foreach ($order as $key=>$val)
            {
                if(is_numeric($key))
                {
                    $array[] =  $val;
                }else{
                    $array[] =  $key.' '.$val;
                }
            }
            $order  = implode(',',$array);
        }
        return !empty($order) ? ' ORDER BY '.$order : '';
    }

    /**
     * limit分析
     * @param $lmit mixed
     * @return string
     */
    private function parseLimit($limit)
    {
        return !empty($limit) ? ' LIMIT '.$limit.' ' : '';
    }

    /**
     * union分析
     * @param $union mixed
     * @return string
     */
    private function parseUnion($union)
    {
        if(empty($union)) return '';
        if(isset($union['_all']))
        {
            $str  =   'UNION ALL ';
            unset($union['_all']);
        }
        else
        {
            $str  =   'UNION ';
        }
        foreach ($union as $u)
        {
            $sql[] = $str.(is_array($u)?$this->buildSelectSql($u):$u);
        }
        return implode(' ',$sql);
    }

    /**
     * comment分析
     * @param $comment string
     * @return string
     */
    private function parseComment($comment)
    {
        return  !empty($comment) ? ' /* '.$comment.' */' : '';
    }

    /**
     * 设置锁机制
     * @return string
     */
    private function parseLock($lock=false)
    {
        if(!$lock) return '';
        if('oracle' == $this->config['driver'])
        {
            return ' FOR UPDATE NOWAIT ';
        }
        return ' FOR UPDATE ';
    }

    /**
     * value分析
     * @param mixed $value
     * @return string
     */
    protected function parseValue($value)
    {
        if(is_string($value))
        {
            $value =  '\''.$this->escapeString($value).'\'';
        }
        elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp')
        {
            $value =  $this->escapeString($value[1]);
        }
        elseif(is_array($value))
        {
            $value =  array_map(array($this, 'parseValue'),$value);
        }
        elseif(is_bool($value))
        {
            $value =  $value ? '1' : '0';
        }
        elseif(is_null($value))
        {
            $value =  'null';
        }
        return $value;
    }

    /**
     * SQL指令安全过滤
     * @access public
     * @param string $str  SQL字符串
     * @return string
     */
    public function escapeString($str)
    {
        return addslashes($str);
    }
}
?>