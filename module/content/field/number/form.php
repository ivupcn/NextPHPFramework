	function number($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = unserialize($setting);
		$size = $setting['size'];		
		if(!$value) $value = $defaultvalue;
		return "<input type='text' name='info[$field]' id='$field' value='$value' class='input-text' size='$size' {$formattribute} {$css}>";
	}
