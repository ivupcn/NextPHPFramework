	function image($field, $value, $fieldinfo) {
		$setting = json_decode($fieldinfo['setting'],true);
		extract($setting);
		$html = '';
		if (defined('IN_ADMIN')) {
			$html = "<input type=\"button\" style=\"width: 66px;\" class=\"button bgn\" onclick=\"crop_cut_".$field."($('#$field').val());return false;\" value=\"裁切图片\"><input type=\"button\" style=\"width: 66px;\" class=\"button bgn\" onclick=\"$('#".$field."_preview').attr('src','icon/upload-pic.png');$('#".$field."').val(' ');return false;\" value=\"取消图片\">";
			}
		$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
		$str = '';
		if($show_type && defined('IN_ADMIN')) {
			$preview_img = $value ? $value : 'icon/upload-pic.png';
			return $str."<div class='upload-pic img-wrap'><input type='hidden' name='info[$field]' id='$field' value='$value'>
			<a href='javascript:void(0);' onclick=\"flashupload('{$field}_images', '附件上传','{$field}',thumb_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','content','$this->catid','$authkey');return false;\">
			<img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>".$html."</div>";
		} else {
			return $str."<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <input type='button' class='button' onclick=\"flashupload('{$field}_images', '附件上传','{$field}',submit_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','content','$this->catid','$authkey')\"/ value='上传图片'>".$html;
		}
	}
