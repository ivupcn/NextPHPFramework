<?php
class form {
	/**
	 * 验证码
	 * @param string $id            生成的验证码ID
	 * @param integer $code_len     生成多少位验证码
	 * @param integer $font_size    验证码字体大小
	 * @param integer $width        验证图片的宽
	 * @param integer $height       验证码图片的高
	 * @param string $font          使用什么字体，设置字体的URL
	 * @param string $font_color    字体使用什么颜色
	 * @param string $background    背景使用什么颜色
	 */
	public static function checkcode($id = 'checkcode',$code_len = 4, $font_size = 20, $width = 130, $height = 50, $font = '', $font_color = '', $background = '')
	{
		return "<img id='$id' onclick='this.src=this.src+\"&\"+Math.random()' src='api.php?m=admin&a=checkcode&code_len=$code_len&font_size=$font_size&width=$width&height=$height&font_color=".urlencode($font_color)."&background=".urlencode($background)."'>";
	}


	/**
	 * 复选框
	 * 
	 * @param $array 选项 二维数组
	 * @param $id 默认选中值，多个用 '逗号'分割
	 * @param $str 属性
	 * @param $defaultvalue 是否增加默认值 默认值为 -99
	 * @param $width 宽度
	 */
	public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '')
	{
		$string = '';
		$id = trim($id);
		if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
		if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
		$i = 1;
		foreach($array as $key=>$value)
		{
			$key = trim($key);
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			if($width) $string .= '<label class="ib" style="width:'.$width.'px">';
			$string .= '<input type="checkbox" '.$str.' id="'.$field.'_'.$i.'" '.$checked.' value="'.htmlspecialchars($key).'"> '.htmlspecialchars($value);
			if($width) $string .= '</label>';
			$i++;
		}
		return $string;
	}

	/**
	 * 下拉选择框
	 */
	public static function select($array = array(), $id = 0, $str = '', $default_option = '')
	{
		$string = '<select '.$str.'>';
		$default_selected = (empty($id) && $default_option) ? 'selected' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		if(!is_array($array) || count($array)== 0) return false;
		$ids = array();
		if(isset($id)) $ids = explode(',', $id);
		foreach($array as $key=>$value)
		{
			$selected = in_array($key, $ids) ? 'selected' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$string .= '</select>';
		return $string;
	}

	/**
	 * 栏目选择
	 * @param string $file 栏目缓存文件名
	 * @param intval/array $catid 别选中的ID，多选是可以是数组
	 * @param string $str 属性
	 * @param string $default_option 默认选项
	 * @param intval $modelid 按所属模型筛选
	 * @param intval $type 栏目类型
	 * @param intval $onlysub 只可选择子栏目
	 * @param intval $siteid 如果设置了siteid 那么则按照siteid取
	 */
	public static function select_category($file = '',$catid = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0,$siteid = 0,$is_push = 0)
	{
		$tree = new tree();
		if(!$siteid) $siteid = context::instance()->get_cookie('siteid');
		if (!$file)
		{
			$file = 'category_content_'.$siteid;
		}
		$categorys = array();
		$result = getcache($file,'content');
		$string = '<select '.$str.'>';
		if($default_option) $string .= "<option value='0'>$default_option</option>";
		//加载权限表模型 ,获取会员组ID值,以备下面投入判断用
		if($is_push=='1')
		{
			$user_groupid = context::instance()->get_cookie('groupid') ? context::instance()->get_cookie('groupid') : null;
		}
		if (is_array($result))
		{
			foreach($result as $r)
			{
 				//检查当前会员组，在该栏目处是否允许投稿？
				if($is_push=='1' and $r['child']=='0')
				{
					$sql = array('catid'=>$r['catid'],'roleid'=>$user_groupid,'action'=>'add');
					$array = admin_model_tagpriv::model()->WHERE($sql)->select(1);
					if(!$array)
					{
						continue;	
					}
				}
				if($siteid != $r['siteid'] || ($type >= 0 && $r['type'] != $type)) continue;
				$r['selected'] = '';
				if(is_array($catid))
				{
					$r['selected'] = in_array($r['catid'], $catid) ? 'selected' : '';
				}
				elseif(is_numeric($catid))
				{
					$r['selected'] = $catid==$r['catid'] ? 'selected' : '';
				}
				$r['html_disabled'] = "0";
				if (!empty($onlysub) && $r['child'] != 0)
				{
					$r['html_disabled'] = "1";
				}
				$categorys[$r['catid']] = $r;
				if($modelid && $r['modelid']!= $modelid ) unset($categorys[$r['catid']]);
			}
		}
		$str  = "<option value='\$catid' \$selected>\$spacer \$catname</option>;";
		$str2 = "<optgroup label='\$spacer \$catname'></optgroup>";

		$tree->init($categorys);
		$string .= $tree->get_tree_category(0, $str, $str2);
			
		$string .= '</select>';
		return $string;
	}

	public static function editor()
	{

	}

	public static function radio()
	{
		
	}

	/**
	 * 日期时间控件
	 * 
	 * @param $name 控件name，id
	 * @param $value 选中值
	 * @param $isdatetime 是否显示时间
	 */
	public static function date($name, $value = '', $isdatetime = 0) {
		if($value == '0000-00-00 00:00:00') $value = '';
		if($isdatetime) {
			$format = 'yyyy-MM-dd HH:mm:ss';			
		} else {
			$format = 'yyyy-MM-dd';
		}
		$str = '';
		$str .= '<input type="text" name="'.$name.'" value="'.$value.'" class="date" dateFmt="'.$format.'" readonly="true" size="26" />';
		return $str;
	}

	/**
	 * 
	 * @param string $name 表单名称
	 * @param int $id 表单id
	 * @param string $value 表单默认值
	 * @param string $moudle 模块名称
	 * @param int $catid 栏目id
	 * @param int $size 表单大小
	 * @param string $class 表单风格
	 * @param string $ext 表单扩展属性 如果 js事件等
	 * @param string $alowexts 允许图片格式
	 * @param array $thumb_setting 
	 * @param int $watermark_setting  0或1
	 */
	public static function images($name, $id = '', $value = '', $moudle='', $catid='', $size = 50, $class = '', $ext = '', $alowexts = '',$thumb_setting = array(),$watermark_setting = 0 ) {
		if(!$id) $id = $name;
		if(!$size) $size= 50;
		if(!empty($thumb_setting) && count($thumb_setting)) $thumb_ext = $thumb_setting[0].','.$thumb_setting[1];
		else $thumb_ext = ',';
		if(!$alowexts) $alowexts = 'jpg|jpeg|gif|bmp|png';
		$authkey = upload_key("1,$alowexts,1,$thumb_ext,$watermark_setting");
		return "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/><a class=\"button\" onclick=\"javascript:flashupload('{$id}_images', '上传附件','{$id}',submit_images,'1,{$alowexts},1,{$thumb_ext},{$watermark_setting}','{$moudle}','{$catid}','{$authkey}')\"><span>上传附件</span></a>";
	}
}
?>