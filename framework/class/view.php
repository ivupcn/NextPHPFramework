<?php
final class NView
{
		
	/**
	 * 编译模板
	 *
	 * @param $module	模块名称
	 * @param $view		模板文件名
	 * @param $istag	是否为标签模板
	 * @return unknown
	 */
	
	public function view_compile($originalviewfile, $compiledviewpath, $compiledviewfile)
	{
		$content = @file_get_contents($originalviewfile);
	    if(!is_dir($compiledviewpath))
	    {
			mkdir($compiledviewpath, 0777, true);
	    }
		$compiledtplfile = $compiledviewpath.$compiledviewfile;
		$content = $this->view_parse($content);
		$strlen = file_put_contents ( $compiledtplfile, $content );
		chmod ( $compiledtplfile, 0777 );
		return $strlen;
    }
	
	/**
	 * 更新模板缓存
	 *
	 * @param $tplfile	模板原文件路径
	 * @param $compiledtplfile	编译完成后，写入文件名
	 * @return $strlen 长度
	 */
	public function view_refresh($tplfile, $compiledtplfile)
	{
		$str = @file_get_contents ($tplfile);
		$str = $this->view_parse ($str);
		$strlen = file_put_contents ($compiledtplfile, $str );
		chmod ($compiledtplfile, 0777);
		return $strlen;
	}
	

	/**
	 * 解析模板
	 *
	 * @param $str	模板内容
	 * @return ture
	 */
	public function view_parse($str)
	{
		$str = preg_replace( '/\{view\s+(.+)\}/', '<?php include $this->view(\\1); ?>', $str );
		$str = preg_replace( '/\{url\s+([^}]+)\}/', '<?php echo $this->_context->url(\\1); ?>', $str );
		$str = preg_replace( "/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str );
		$str = preg_replace( "/\{php\s+(.+)\}/", "<?php \\1?>", $str );
		$str = preg_replace( "/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str );
		$str = preg_replace( "/\{else\}/", "<?php } else { ?>", $str );
		$str = preg_replace( "/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str );
		$str = preg_replace( "/\{\/if\}/", "<?php } ?>", $str );
		$str = preg_replace( '/\{{(.+)\}}/', "<?php echo'&#123;\\1&#125;'; ?>", $str );
		//for 循环
		$str = preg_replace("/\{for\s+(.+?)\}/","<?php for(\\1) { ?>",$str);
		$str = preg_replace("/\{\/for\}/","<?php } ?>",$str);
		//++ --
		$str = preg_replace("/\{\+\+(.+?)\}/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/\{\-\-(.+?)\}/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/\{(.+?)\+\+\}/","<?php \\1++; ?>",$str);
		$str = preg_replace("/\{(.+?)\-\-\}/","<?php \\1--; ?>",$str);
		$str = preg_replace( "/\{nloop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
		$str = preg_replace( "/\{nloop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
		$str = preg_replace( "/\{\/nloop\}/", "<?php \$n++;}unset(\$n); ?>", $str );
		$str = preg_replace( "/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
		$str = preg_replace( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
		$str = preg_replace( "/\{\/loop\}/", "<?php } ?>", $str );
		$str = preg_replace( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace( "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace( "/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1;?>')",$str);
		$str = preg_replace( "/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str );
		$str = preg_replace("/\{model:(\w+)\s+([^}]+)\}/ie", "self::model_tag('$1','$2', '$0')", $str);
		$str = preg_replace("/\{\/model\}/ie", "self::end_model_tag()", $str);
		$str = "<?php defined('IN_Next') or exit('No permission resources.'); ?>" . $str;
		return $str;
	}

	/**
	 * 转义 // 为 /
	 *
	 * @param $var	转义的字符
	 * @return 转义后的字符
	 */
	public function addquote($var)
	{
		return str_replace ( "\\\"", "\"", preg_replace ( "/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var ) );
	}
	
	/**
	 * 解析x标签
	 * @param string $model 数据模型
	 * @param string $data 参数
	 * @param string $html 匹配到的所有的HTML代码
	 */
	public static function model_tag($model, $data, $html)
	{
		preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($data), $matches, PREG_SET_ORDER);
		$tools = array('select', 'listinfo', 'get_one', 'query', 'insert', 'update', 'delete', 'count');
		$datas = array();
		$tag_id = md5(stripslashes($html));
		//可视化条件
		$str_datas = 'model='.$model.'&tag_md5='.$tag_id;
		foreach ($matches as $v)
		{
			$str_datas .= $str_datas ? "&$v[1]=".urlencode($v[2]) : "$v[1]=".(strpos($v[2], '$') === 0 ? $v[2] : urlencode($v[2]));
			$datas[$v[1]] = $v[2];
		}
		$str = '';
		if(isset($datas['action']) && in_array($datas['action'],$tools))
		{
			$return = isset($datas['return']) && trim($datas['return']) ? trim($datas['return']) : 'data';
			$where = isset($datas['where']) ? "\"".trim($datas['where'])."\"" : "''";
			$data = isset($datas['data']) ? "'".trim($datas['data'])."'" : "'*'";
			$limit = isset($datas['limit']) ? "'".trim($datas['limit'])."'" : "''";
			$order = isset($datas['order']) ? "'".trim($datas['order'])."'" : "''";
			$group = isset($datas['group']) ? "'".trim($datas['group'])."'" : "''";
			$key = isset($datas['key']) ? "'".trim($datas['key'])."'" : "''";
			$page = isset($datas['page']) && intval($datas['page']) ? intval($datas['page']) : "1";
			$pagesize = isset($datas['pagesize']) && intval($datas['pagesize']) > 0 ? "'".intval($datas['pagesize'])."'" : "20";
			$setpages = isset($datas['setpages']) && intval($datas['setpages']) ? "'".intval($datas['setpages'])."'" : "10";
			$urlrule = isset($datas['urlrule']) ? "'".trim($datas['urlrule'])."'" : "''";
			$sql = isset($datas['sql']) ? "'".trim($datas['sql'])."'" : "''";
			$params = isset($datas['params']) ? "'".trim($datas['params'])."'" : "''";
			switch($datas['action'])
			{
				case 'select':
					$str .= '$'.$return.'='.$model.'::model()->'.$datas['action'].'('.$where.','.$data.','.$limit.','.$order.','.$group.','.$key.');';
					break;
				case 'listinfo':
					$str .= '$page = max(intval($page), 1);';
					$str .= '$offset = '.$pagesize.'*($page-1);';
					$str .= '$'.$return.'='.$model.'::model()->select('.$where.','.$data.',$offset.\','.$pagesize.'\','.$order.','.$group.','.$key.');$count = '.$model.'::model()->get_one('.$where.', "COUNT(*) AS num");$pages = pages($count["num"], $page, '.$pagesize.');';
					break;
				case 'get_one':
					$str .= '$'.$return.'='.$model.'::model()->'.$datas['action'].'('.$where.','.$data.','.$order.','.$group.');';
					break;
				case 'query':
					$str .= '$'.$return.'='.$model.'::model()->'.$datas['action'].'('.$sql.','.$params.');';
					break;
				case 'insert':
					break;
				case 'update':
					break;
				case 'delete':
					break;
				case 'count':
					break;
			}
		}
		return "<"."?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo \"<div class=\\\"admin_piao\\\" next_action=\\\"".$model."\\\" data=\\\"".$str_datas."\\\"><a href=\\\"javascript:void(0)\\\" class=\\\"admin_piao_edit\\\">编辑</a>\";}".$str."?".">";
	}
	
	/**
	 * X标签结束
	 */
	static private function end_model_tag()
	{
		return '<?php if(defined(\'IN_ADMIN\') && !defined(\'HTML\')) {echo \'</div>\';}?>';
	}
	
	/**
	 * 转换数据为HTML代码
	 * @param array $data 数组
	 */
	private static function arr_to_html($data)
	{
		if (is_array($data))
		{
			$str = 'array(';
			foreach ($data as $key=>$val)
			{
				if (is_array($val))
				{
					$str .= "'$key'=>".self::arr_to_html($val).",";
				}
				else
				{
					if (strpos($val, '$')===0)
					{
						$str .= "'$key'=>$val,";
					}
					else
					{
						$str .= "'$key'=>'".new_addslashes($val)."',";
					}
				}
			}
			return $str.')';
		}
		return false;
	}
}
?>
