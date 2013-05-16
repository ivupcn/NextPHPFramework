	function groupid($field, $value, $fieldinfo) {
		extract(unserialize($fieldinfo['setting']));
		$grouplist = getcache('grouplist_'.SITEID,'user');
		foreach($grouplist as $_key=>$_value) {
			$data[$_key] = $_value['name'];
		}
		return '<input type="hidden" name="info['.$field.']" value="1">'.form::checkbox($data,$value,'name="'.$field.'[]" id="'.$field.'"','','120');
	}
