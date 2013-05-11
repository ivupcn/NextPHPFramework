	function catid($field, $value, $fieldinfo) {
		if(!$value) $value = $this->catid;
		$publish_str = '';
		if(defined('IN_ADMIN') && ROUTE_A=='add') $publish_str = " <a href='javascript:;' onclick=\"omnipotent('selectid','?m=content&c=content&a=add_othors&siteid=".$this->siteid."','同时发布到其他栏目',1);return false;\" style='color:#B5BFBB'>[同时发布到其他栏目]</a><ul class='list-dot-othors' id='add_othors_text'></ul>";
		if(!empty($value))
		{
			$publish_str .=$this->categorys[$value]['catname'];
		}
		return '<input type="hidden" name="info['.$field.']" value="'.$value.'">'.$publish_str;
	}
