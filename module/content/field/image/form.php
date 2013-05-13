	function image($field, $value, $fieldinfo) {
		$setting = json_decode($fieldinfo['setting'],true);
		extract($setting);
		$html = '';
		$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
		$str = '';
		return $str."<input type='text' name='info[$field]' id='$field' value='$value' size='$size' class='input-text' />  <a class='button' href=\"javascript:flashupload('{$field}_images', '附件上传','{$field}',submit_images,'1,{$upload_allowext},$isselectimage,$images_width,$images_height,$watermark','content','$this->catid','$authkey');\"><span>上传图片</span></a>".$html;
	}
