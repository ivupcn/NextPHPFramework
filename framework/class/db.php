<?php
class db
{
	//静态变量 保存全局实例
	static private $_instance = NULL;
	//数据库配置
	static private $define = array();
	//数据库连接
	protected $db = '';
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
	// 数据库表达式
	protected $comparison = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN','findinset'=>'find_in_set');
	//链操作方法列表
	protected $sql_methods = array('field','where','table','join','union','order','limit','alias','having','group','lock','distinct','set','inserttype','page','fieldvalue','sql');
    //SQL属性
	protected $sql_options = array();
	//分页
	public $pages = null;

    /**
     *  私有化构造函数，防止外界实例化对象
	 */
	private function  __construct($class)
	{
		$this->open();
	}

    /**
     *  私有化克隆函数，防止外界克隆对象
     */
	private function  __clone(){}

    /**
     *  静态方法, 单例统一访问入口
     *  @return  object  返回对象的唯一实例
     */
	static public function getInstance($class)
	{
		self::$define = (array)call_user_func(array($class, '__define'));
		if(!isset(self::$define['db_config']))
		{
			NLOG::error('    Database configuration information can not be empty');
		}
        if (is_null(self::$_instance) || !isset(self::$_instance))
        {
            self::$_instance = new self($class);
        }
        return self::$_instance;
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		if(is_resource($this->link))
        {
            $this->link = null;
        }
	}

	/**
	 * 打开数据库连接,有可能不真实连接数据库
	 * @param $config	数据库连接参数		
	 * @return void
	 */
	private function open()
	{
		$this->connect();
	}

	/**
	 * 真正开启数据库连接	
	 * @return void
	 */
	private function connect()
	{
		if(!$this->link = new PDO(self::$define['db_config']['dsn'], self::$define['db_config']['username'], self::$define['db_config']['password'], self::$define['db_config']['attribute']))
		{
			NLOG::error('    Can not connect to database server');
		}		
	}

	/**
     * 利用__call方法实现一些特殊的Model方法
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
	private function __call($method,$args)
	{
		if(in_array(strtolower($method),$this->sql_methods,true))
		{
            // 连贯操作的实现
            $this->sql_options[strtolower($method)] = $args[0];
            return $this;
        }
        elseif(in_array(strtolower($method),array('count','sum','min','max','avg'),true))
        {
            $field =  isset($args[0]) ? $args[0] : '*';
            $table = $this->parseTable(!empty($this->sql_options['table']) ? $this->sql_options['table'] : self::$define['db_config']['tablepre'].self::$define['table_name']);
            $where = $this->parseWhere(!empty($this->sql_options['where']) ? $this->sql_options['where'] : '');
            $sql = 'SELECT '.strtoupper($method).'('.$field.') AS NextPHP_'.$method.' FROM '.$table.$where;
            $this->execute($sql);
            $data = $this->fetchData(1, PDO::FETCH_ASSOC);
            return $data['NextPHP_'.$method];
        }
        else
        {
        	NLOG::error('    sql_options:'.$method.' methond not exist');
            return;
        }
	}

	/**
	 * 查询数据
	 * @return array|object		查询结果集数组
	 */
	public function select($fetchType = 0, $mode = PDO::FETCH_ASSOC)
	{
		if(!empty($this->sql_options['page']))
		{
			$this->pages = $this->parsePage($this->sql_options['page']);
		}
		$sql = $this->buildSql($this->sql_options, 'select');
		$this->execute($sql);
		$datalist = $this->fetchData($fetchType, $mode);
		$this->sql_options = array();
		$this->free_result();
		return $datalist;
	}

	public function update()
	{
		$sql = $this->buildSql($this->sql_options, 'update');
		$r = $this->exec($sql);
		$this->sql_options = array();
		$this->free_result();
		return $r;
	}

	public function delete()
	{
		$sql = $this->buildSql($this->sql_options, 'delete');
		$r = $this->exec($sql);
		$this->sql_options = array();
		$this->free_result();
		return $r;
	}

	public function insert()
	{
		$sql = $this->buildSql($this->sql_options, 'insert');
		$r = $this->exec($sql);
		$insert_id = $this->insert_id();
		$this->sql_options = array();
		$this->free_result();
		return $insert_id;
	}

	public function query($fetchType = 0, $mode = PDO::FETCH_ASSOC)
	{
		$sql = $this->buildSql($this->sql_options);
		$this->lastqueryid = $this->link->query($sql);
		$datalist = $this->fetchData($fetchType, $mode);
		$this->sql_options = array();
		$this->free_result();
		return $datalist;
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
     * 主要是针对没有结果集合返回的操作，比如 INSERT、UPDATE、DELETE 等操作，它返回的结果是当前操作影响的列数。
     * @param $sql 要执行的sql语句
     * @return 当前操作影响的列数
     */
    public function exec($sql)
    {
        NLOG::log('    REQUEST SQL：' . $sql);
        if(!is_resource($this->link))
        {
            $this->connect();
        }
        $row = $this->link->exec($sql);
        return $row;
    }

    public function fetchData($fetchType, $mode)
    {
        $fetch = $fetchType ? 'fetch' : 'fetchall';
        return $this->lastqueryid->$fetch($mode);
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
	 * @param $params string | array
	 * @return string           
	 */
	protected function buildSql($options=array(), $params=null)
	{
		switch($params)
		{
			case 'select':
				$sql = str_replace(
	                array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%OFFSET%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),
	                array(
	                    $this->parseTable(!empty($options['table']) ? $options['table'] : self::$define['db_config']['tablepre'].self::$define['table_name']),
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
	                'SELECT %DISTINCT%%FIELD% FROM %TABLE%%JOIN%%WHERE%%OFFSET%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%'
	            );
	            break;
	        case 'update':
	            $sql = str_replace(
	                array('%TABLE%','%SET%','%WHERE%','%ORDER%','%LIMIT%','%LOCK%','%COMMENT%'),
	                array(
	                    $this->parseTable(!empty($options['table']) ? $options['table'] : self::$define['db_config']['tablepre'].self::$define['table_name']),
	                    $this->parseSet(!empty($options['set']) ? $options['set'] : ''),
	                    $this->parseWhere(!empty($options['where']) ? $options['where'] : ''),
	                    $this->parseOrder(!empty($options['order']) ? $options['order'] : ''),
	                    $this->parseLimit(!empty($options['limit']) ? $options['limit'] : ''),
	                    $this->parseLock(!empty($options['lock']) ? $options['lock'] : ''),
	                    $this->parseComment(!empty($options['comment']) ? $options['comment'] : '')
	                ),
	                'UPDATE %TABLE%%SET%%WHERE%%ORDER%%LIMIT%%LOCK%%COMMENT%'
	            );
	            break;
	        case 'delete':
	            $sql = str_replace(
	                array('%TABLE%','%WHERE%'),
	                array(
	                    $this->parseTable(!empty($options['table']) ? $options['table'] : self::$define['db_config']['tablepre'].self::$define['table_name']),
	                    $this->parseWhere(!empty($options['where']) ? $options['where'] : '')
	                ),
	                'DELETE FROM %TABLE%%WHERE%'
	            );
	            break;
	        case 'insert':
	            $sql = str_replace(
	                array('%INSERT%','%TABLE%','%FIELD%','%VALUE%'),
	                array(
	                    !empty($options['inserttype']) ? $options['inserttype'] : 'INSERT INTO ',
	                    $this->parseTable(!empty($options['table']) ? $options['table'] : self::$define['db_config']['tablepre'].self::$define['table_name']),
	                    $this->parseFieldK(!empty($options['fieldvalue']) ? $options['fieldvalue'] : ''),
	                    $this->parseFieldV(!empty($options['fieldvalue']) ? $options['fieldvalue'] : '')
	                ),
	                '%INSERT%%TABLE% (%FIELD%) VALUES (%VALUE%)'
	            );
	            break;
	        default:
	            $sql = str_replace(
	                array('%SQL%'),
	                array(
	                    $this->parseSql(!empty($options['sql']) ? $options['sql'] : '')
	                ),
	                '%SQL%'
	            );
	            break;
	    }
		return $sql;
	}

	/**
     * 生成查询SQL
     * @param array $options 表达式
     * @return string
     */
    protected function buildSelectSql($options=array())
    {
        if(isset($options['page']))
        {
            // 根据页数计算limit
            if(strpos($options['page'],','))
            {
                list($page,$listRows) = explode(',',$options['page']);
            }
            else
            {
                $page = $options['page'];
            }
            $page    =  $page?$page:1;
            $listRows=  isset($listRows) ? $listRows : (is_numeric($options['limit']) ? $options['limit'] : 20);
            $offset  =  $listRows*((int)$page-1);
            $options['limit'] =  $offset.','.$listRows;
        }
        $sql = $this->parseSql($options,'select');
        $sql .= $this->parseLock(isset($options['lock'])?$options['lock']:false);
        return $sql;
    }

    /*
     * 分析SQL语句
     * @param $sql array
     * @return string 
     */
    protected function parseSql($expression)
    {
        if(is_array($expression))
        {
            $parts = explode('?', $expression[0]);
            $sql = $parts[0];
            $offset = 1;
            foreach($expression[1] as $arg_value)
            {
                if (!isset($parts[$offset]))
                {
                    break;
                }
                $sql .= $this->parseValue($arg_value) . $parts[$offset];
                $offset ++;
            }
        }
        else
        {
            $sql = $expression;
        }
        return $sql;
    }

    /*
     * 分页分页 PAGE(array('page'=>1,'pagesize'=>20,'target'=>'','setpages'=>10,'urlrule'=>array(),'array'=>array()))
     * @param $page array
     * @return array
     */
    protected function parsePage($pages)
    {
    	if(is_array($pages))
    	{
    		$page = isset($pages['page']) && intval($pages['page']) ? max($pages['page'],1) : 1;
    		$pagesize = isset($pages['pagesize']) && intval($pages['pagesize']) ? $pages['pagesize'] : 20;
    		$target = isset($pages['target']) && is_string($pages['target']) && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]+$/', $pages['traget']) ? trim($pages['target']) : 'targetType="navTab"';
    		$setpages = isset($pages['setpages']) && intval($pages['setpages']) ? $pages['setpages'] : 10;
    		$urlrule = isset($pages['urlrule']) && is_array($pages['urlrule']) && !empty($pages['urlrule']) ? $pages['urlrule'] : array('','page={$page}');
    		$array = isset($pages['array']) && is_array($pages['array']) && !empty($pages['array']) ? $pages['array'] : array();
            $func = isset($pages['func']) ? trim($pages['func']) : 'ROW_NUMBER';
    		
    		$total = $this->COUNT();
			$offset = $pagesize*($page-1);

            $this->sql_options['limit'] = $offset.', '.$pagesize;
			if(defined('IN_ADMIN'))
			{
				return '<div class="pages"><span>共'.$total.'条</span></div><div class="pagination" '.$target.' totalCount="'.$total.'" numPerPage="'. $pagesize.'" pageNumShown="10" currentPage="'.$page.'"></div>';
			}
			else
			{
				return pages($total, $page, $pagesize, $urlrule, $array, $setpages);
			}
    	}
    	else
    	{
    		return null;
    	}
    }

	/*
	 * 分析表
	 * @param $table mixed
	 * @return string 
	 */
	protected function parseTable($tables)
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
    protected function parseDistinct($distinct)
    {
        return !empty($distinct) ? 'DISTINCT ' : '';
    }

    /**
     * field分析
     * @param $fields mixed
     * @return string
     */
    protected function parseField($fields)
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
    protected function parseJoin($join)
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
    protected function parseWhere($where)
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
                        elseif(preg_match('/^(FINDINSET)$/i',$val[0]))
                        {
                        	$whereStr .= $this->comparison[strtolower($val[0])].'('.$this->parseValue($val[1]).','.$key.')';
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
    protected function parseGroup($group)
    {
        return !empty($group) ? ' GROUP BY '.$group : '';
    }

    /**
     * having分析
     * @param $having string
     * @return string
     */
    protected function parseHaving($having)
    {
        return !empty($having) ? ' HAVING '.$having : '';
    }

    /**
     * order分析
     * @param $order mixed
     * @return string
     */
    protected function parseOrder($order)
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
    protected function parseLimit($limit)
    {
        return !empty($limit) ? ' LIMIT '.$limit.' ' : '';
    }

    /**
     * union分析
     * @param $union mixed
     * @return string
     */
    protected function parseUnion($union)
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
    protected function parseComment($comment)
    {
        return  !empty($comment) ? ' /* '.$comment.' */' : '';
    }

    /**
     * 设置锁机制
     * @return string
     */
    protected function parseLock($lock=false)
    {
        if(!$lock) return '';
        $dsn = parse_url(self::$define['db_config']['dsn']);
        if('oracle' == $dsn['scheme'])
        {
            return ' FOR UPDATE NOWAIT ';
        }
        return ' FOR UPDATE ';
    }

    /**
     * set分析
     * @param array $data
     * @return string
     */
    protected function parseSet($data)
    {
        foreach($data as $key=>$val)
        {
            $value = $this->parseValue($val);
            if(is_scalar($value))
            {// 过滤非标量数据
                $set[] = $key.'='.$value;
            }
        }
        return ' SET '.implode(',',$set);
    }

    /**
     * FieldK分析
     * @param array $data
     * @return string
     */
    protected function parseFieldK($fieldvalue)
    {
        $key = array_keys($fieldvalue);
        return implode(',',$key);
    }

    /**
     * FieldV分析
     * @param array $data
     * @return string
     */
    protected function parseFieldV($fieldvalue)
    {
        $key = $this->parseValue(array_values($fieldvalue));
        return implode(',',$key);
    }

    /**
     * value分析
     * @param mixed $value
     * @return string
     */
    public function parseValue($value)
    {
        if(is_string($value) || $value === 0)
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

    /**
     * 对数据进行验证，返回所有未通过验证数据的错误信息
     *
     * @param array $data 要验证的数据
     * @param steing $reject 不需要验证的数据,多个字段属性用','分隔
     * @return array 所有没有通过验证的属性名称及验证规则
     */
    public function validate(array $data, $reject = null)
    {
    	if($reject != null)
        {
        	$reject_arr = normalize($reject,',');
        	foreach($reject_arr as $rk => $rv)
        	{
        		unset(self::$define['validations'][$rv]);
        	}
        }
    	$error = '';
    	foreach (self::$define['validations'] as $prop => $policy)
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