	function datetime($field, $value, $fieldinfo) {
		extract(json_decode($fieldinfo['setting'],true));
		$isdatetime = 0;
		if($fieldtype=='int') {
			if(!$value) $value = SYS_TIME;
			$format_txt = $format == 'm-d' ? 'm-d' : $format;
			if($format == 'Y-m-d Ah:i:s') $format_txt = 'Y-m-d h:i:s';
			$value = date($format_txt,$value);
			
			$isdatetime = strlen($format) > 6 ? 1 : 0;			
		} elseif($fieldtype=='datetime') {
			$isdatetime = 1;
		} elseif($fieldtype=='datetime_a') {
			$isdatetime = 1;
		}
		return form::date("info[$field]",$value,$isdatetime);
	}
