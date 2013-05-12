	function linkage($field, $value, $fieldinfo) {
		$setting = json_decode($fieldinfo['setting'],true);
		$linkageid = $setting['linkageid'];
		return menu_linkage($linkageid,$field,$value);
	}
