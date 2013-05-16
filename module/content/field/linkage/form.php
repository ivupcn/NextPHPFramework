	function linkage($field, $value, $fieldinfo) {
		$setting = unserialize($fieldinfo['setting']);
		$linkageid = $setting['linkageid'];
		return menu_linkage($linkageid,$field,$value);
	}
