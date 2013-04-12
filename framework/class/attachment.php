<?php 
class attachment {
	var $field;
	var $imageexts = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	var $uploadedfiles = array();
	var $error;
	
	
	function __construct() {}
	/**
	 * 附件上传方法
	 * @param $field 上传字段
	 */
	function upload($field,$alowexts,$maxsize,$uploadrootpath,$overwrite = 0)
	{
		if(!isset($_FILES[$field]))
		{
			$this->error = UPLOAD_ERR_OK;
			return false;
		}
			
		$this->field = $field;
		$this->savepath = $uploadrootpath.date('Y/md/');
		$this->alowexts = $alowexts;
		$uploadfiles = array();
		$description = isset($GLOBALS[$field.'_description']) ? $GLOBALS[$field.'_description'] : array();
		if(is_array($_FILES[$field]['error'])) {
			$this->uploads = count($_FILES[$field]['error']);
			foreach($_FILES[$field]['error'] as $key => $error) {
				if($error === UPLOAD_ERR_NO_FILE) continue;
				if($error !== UPLOAD_ERR_OK) {
					$this->error = $error;
					return false;
				}
				$uploadfiles[$key] = array('tmp_name' => $_FILES[$field]['tmp_name'][$key], 'name' => $_FILES[$field]['name'][$key], 'type' => $_FILES[$field]['type'][$key], 'size' => $_FILES[$field]['size'][$key], 'error' => $_FILES[$field]['error'][$key], 'description'=>$description[$key]);
			}
		} else {
			$this->uploads = 1;
			if(!$description) $description = '';
			$uploadfiles[0] = array('tmp_name' => $_FILES[$field]['tmp_name'], 'name' => $_FILES[$field]['name'], 'type' => $_FILES[$field]['type'], 'size' => $_FILES[$field]['size'], 'error' => $_FILES[$field]['error'], 'description'=>$description);
		}

		if(!dir::dir_create($this->savepath)) {
			$this->error = '8';
			return false;
		}
		if(!is_dir($this->savepath)) {
			$this->error = '8';
			return false;
		}
		@chmod($this->savepath, 0777);

		if(!is_writeable($this->savepath)) {
			$this->error = '9';
			return false;
		}
		$aids = array();
		foreach($uploadfiles as $k=>$file)
		{
			$fileext = fileext($file['name']);
			if($file['error'] != 0) {
				$this->error = $file['error'];
				return false;				
			}
			if(!preg_match("/^(".$this->alowexts.")$/", $fileext)) {
				$this->error = '10';
				return false;
			}
			if($maxsize && $file['size'] > $maxsize) {
				$this->error = '11';
				return false;
			}
			if(!$this->isuploadedfile($file['tmp_name'])) {
				$this->error = '12';
				return false;
			}
			$temp_filename = $this->getname($fileext);
			$savefile = $this->savepath.$temp_filename;
			$savefile = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", $savefile);
			$filepath = preg_replace(new_addslashes("|^".$uploadrootpath."|"), "", $savefile);
			if(!$overwrite && file_exists($savefile)) continue;
			if(copy($file['tmp_name'], $savefile))
			{
				$this->uploadeds++;
				@chmod($savefile, 0644);
				@unlink($file['tmp_name']);
				$isimage = in_array($fileext, $this->imageexts) ? 'image' : 0;
				$uploadedfile = array('filename'=>$file['name'], 'filepath'=>$filepath, 'filesize'=>$file['size'], 'fileext'=>$fileext, 'isimage'=>$isimage);
				$this->uploadedfiles[] = $uploadedfile;
			}
		}
		return $this->uploadedfiles;
	}
	
	/**
	 * 获取缩略图地址..
	 * @param $image 图片路径
	 */
	function get_thumb($image){
		return str_replace('.', '_thumb.', $image);
	}


	/**
	 * 获取附件名称
	 * @param $fileext 附件扩展名
	 */
	function getname($fileext){
		return date('Ymdhis').rand(100, 999).'.'.$fileext;
	}

	/**
	 * 返回附件大小
	 * @param $filesize 图片大小
	 */
	
	function size($filesize) {
		if($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
		} elseif($filesize >= 1048576) {
			$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
		} elseif($filesize >= 1024) {
			$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
		} else {
			$filesize = $filesize . ' Bytes';
		}
		return $filesize;
	}
	/**
	* 判断文件是否是通过 HTTP POST 上传的
	*
	* @param	string	$file	文件地址
	* @return	bool	所给出的文件是通过 HTTP POST 上传的则返回 TRUE
	*/
	function isuploadedfile($file) {
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}
	
	/**
	* 补全网址
	*
	* @param	string	$surl		源地址
	* @param	string	$absurl		相对地址
	* @param	string	$basehref	网址
	* @return	string	网址
	*/
	function fillurl($surl, $absurl, $basehref = '') {
		if($basehref != '') {
			$preurl = strtolower(substr($surl,0,6));
			if($preurl=='http://' || $preurl=='ftp://' ||$preurl=='mms://' || $preurl=='rtsp://' || $preurl=='thunde' || $preurl=='emule://'|| $preurl=='ed2k://')
			return  $surl;
			else
			return $basehref.'/'.$surl;
		}
		$i = 0;
		$dstr = '';
		$pstr = '';
		$okurl = '';
		$pathStep = 0;
		$surl = trim($surl);
		if($surl=='') return '';
		$urls = @parse_url(SITE_URL);
		$HomeUrl = $urls['host'];
		$BaseUrlPath = $HomeUrl.$urls['path'];
		$BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/",'/',$BaseUrlPath);
		$BaseUrlPath = preg_replace("/\/$/",'',$BaseUrlPath);
		$pos = strpos($surl,'#');
		if($pos>0) $surl = substr($surl,0,$pos);
		if($surl[0]=='/') {
			$okurl = 'http://'.$HomeUrl.'/'.$surl;
		} elseif($surl[0] == '.') {
			if(strlen($surl)<=2) return '';
			elseif($surl[0]=='/') {
				$okurl = 'http://'.$BaseUrlPath.'/'.substr($surl,2,strlen($surl)-2);
			} else {
				$urls = explode('/',$surl);
				foreach($urls as $u) {
					if($u=="..") $pathStep++;
					else if($i<count($urls)-1) $dstr .= $urls[$i].'/';
					else $dstr .= $urls[$i];
					$i++;
				}
				$urls = explode('/', $BaseUrlPath);
				if(count($urls) <= $pathStep)
				return '';
				else {
					$pstr = 'http://';
					for($i=0;$i<count($urls)-$pathStep;$i++) {
						$pstr .= $urls[$i].'/';
					}
					$okurl = $pstr.$dstr;
				}
			}
		} else {
			$preurl = strtolower(substr($surl,0,6));
			if(strlen($surl)<7)
			$okurl = 'http://'.$BaseUrlPath.'/'.$surl;
			elseif($preurl=="http:/"||$preurl=='ftp://' ||$preurl=='mms://' || $preurl=="rtsp://" || $preurl=='thunde' || $preurl=='emule:'|| $preurl=='ed2k:/')
			$okurl = $surl;
			else
			$okurl = 'http://'.$BaseUrlPath.'/'.$surl;
		}
		$preurl = strtolower(substr($okurl,0,6));
		if($preurl=='ftp://' || $preurl=='mms://' || $preurl=='rtsp://' || $preurl=='thunde' || $preurl=='emule:'|| $preurl=='ed2k:/') {
			return $okurl;
		} else {
			$okurl = preg_replace('/^(http:\/\/)/i','',$okurl);
			$okurl = preg_replace('/\/{1,}/i','/',$okurl);
			return 'http://'.$okurl;
		}
	}
	
	/**
	 * 返回错误信息
	 */
	function error() {
		$UPLOAD_ERROR = array(
		0 => '文件上传成功',
		1 => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值',
		2 => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
		3 => '文件只有部分被上传',
		4 => '没有文件被上传',
		5 => '',
		6 => '找不到临时文件夹。',
		7 => '文件写入临时文件夹失败',
		8 => '附件目录创建不成功',
		9 => '附件目录没有写入权限',
		10 => '不允许上传该类型文件',
		11 => '文件超过了管理员限定的大小',
		12 => '非法上传文件',
		13 => '该用户组禁止上传附件',
		);
		
		return $UPLOAD_ERROR[$this->error];
	}
}
?>