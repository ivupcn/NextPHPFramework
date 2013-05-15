	function editor($field, $value, $fieldinfo) {
		$grouplist = getcache('grouplist','member');
		$_groupid = $_SESSION['groupid'];
		$grouplist = $grouplist[$_groupid];
		extract($fieldinfo);
		extract(unserialize($setting));
		$disabled_page = isset($disabled_page) ? $disabled_page : 0;
		if(!isset($height)) $height = 300;
		$allowupload = defined('IN_ADMIN') ? 1 : $grouplist['allowattachment'] ? 1: 0;
		if(!$value) $value = $defaultvalue;
		if($minlength || $pattern) $allow_empty = '';
		if($minlength) $this->formValidator .= '$("#'.$field.'").formValidator({'.$allow_empty.'onshow:"",onfocus:"'.$errortips.'"}).functionValidator({
	    fun:function(val,elem){
			var oEditor = CKEDITOR.instances.'.$field.';
			var data = oEditor.getData();
	        if($(\'#islink\').attr(\'checked\')){
			    return true;
		    } else if(($(\'#islink\').attr(\'checked\')==false) && (data==\'\')){
			    return "'.$errortips.'";
		    } else if (data==\'\' || $.trim(data)==\'\') {
				return "'.$errortips.'";
			}
			return true;
		}
	});';
		return "<div id='{$field}_tip'></div>".'<textarea class="editor" name="info['.$field.']" style="width:100%; height:400px">'.$value.'</textarea>';
	}
