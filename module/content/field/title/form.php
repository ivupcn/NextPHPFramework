	function title($field, $value, $fieldinfo) {
		extract($fieldinfo);
		if(!$value) $value = isset($defaultvalue) ? $defaultvalue : '';
		$errortips = $this->fields[$field]['errortips'];
		$errortips_max = '标题不能为空';
		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
		$str = '<input type="text" name="info['.$field.']" style="width:500px;" id="'.$field.'" value="'.$value.'" class="measure-input " onBlur="$.post(\'api.php?op=get_keywords&number=3&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data && $(\'#keywords\').val()==\'\') $(\'#keywords\').val(data); })" onkeyup="strlen_verify(this, \'title_len\', '.$maxlength.');" />';
		$str .= '还可输入 <B><span id="title_len">'.$maxlength.'</span></B> 个字符';
		return $str;
	}