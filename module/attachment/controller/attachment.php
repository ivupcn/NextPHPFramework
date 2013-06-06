<?php
class attachment_controller_attachment extends attachment_class_controller
{
	function action_init()
	{
		$swf_auth_key = isset($_GET['swf_auth_key']) ? trim($_GET['swf_auth_key']) : $this->_app->showmessage('300','参数错误！');
		$SWFUPLOADSESSID = isset($_GET['SWFUPLOADSESSID']) ? intval($_GET['SWFUPLOADSESSID']) : $this->_app->showmessage('300','参数错误！');
		$module = isset($_GET['module']) ? trim($_GET['module']) : $this->_app->showmessage('300','参数错误！');
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : $this->_app->showmessage('300','参数错误！');
		$queueSizeLimit = isset($_GET['queueSizeLimit']) ? intval($_GET['queueSizeLimit']) : $this->_app->showmessage('300','参数错误！');
		$fileSizeLimit = isset($_GET['fileSizeLimit']) ? intval($_GET['fileSizeLimit']) : $this->_app->showmessage('300','参数错误！');
		$exts = isset($_GET['fileTypeExts']) ? trim($_GET['fileTypeExts']) : $this->_app->showmessage('300','参数错误！');
		$extsArr = explode('|', $exts);
		$arr = array();
		foreach($extsArr as $ext)
		{
			$arr[] = '*.'.$ext;
		}
		$fileTypeExts = implode(';', $arr);
		include $this->view('attachment','attachment','init');
	}

	function action_swfUpload()
	{
		if($this->_context->isPOST())
		{
			if($_SESSION['roleid'] == 1)
			{
				$groupallowattachment = true;
			}
			else
			{
				$grouplist = getcache('grouplist_'.SITEID,'user');
				$groupallowattachment = $grouplist[$_SESSION['groupid']]['allowattachment'];
			}
			if($_POST['swf_auth_key'] != md5(Next::config('system','auth_key','29HTvKg84Veg8VtDdKbs').$_POST['SWFUPLOADSESSID']) || !$groupallowattachment) exit('该用户组禁止上传附件');
			$site_setting = $this->get_site_setting();
			$alowexts = $site_setting['upload_allowext'];
			$maxsize = $site_setting['upload_maxsize'] * 1024;
			$attachment = new attachment();
			$aids = $attachment->upload('Filedata',$alowexts,$maxsize,$this->upload_path,0);
			NLOG::log($aids);
			if($aids[0])
			{
				$filename= $attachment->uploadedfiles[0]['filename'];
				if($attachment->uploadedfiles[0]['isimage'])
				{
					echo $attachment->uploadedfiles[0]['filename'].','.$this->upload_url.$attachment->uploadedfiles[0]['filepath'].','.$attachment->uploadedfiles[0]['filesize'].','.$attachment->uploadedfiles[0]['fileext'].','.$attachment->uploadedfiles[0]['isimage'];
				}
				else
				{
					$fileext = $attachment->uploadedfiles[0]['fileext'];
					if($fileext == 'zip' || $fileext == 'rar') $fileext = 'rar';
					elseif($fileext == 'mp3') $fileext = 'mp3';
					elseif($fileext == 'doc' || $fileext == 'docx') $fileext = 'doc';
					elseif($fileext == 'xls' || $fileext == 'xlsx') $fileext = 'xls';
					elseif($fileext == 'ppt' || $fileext == 'pptx') $fileext = 'ppt';
					elseif ($fileext == 'flv' || $fileext == 'swf' || $fileext == 'rm' || $fileext == 'rmvb') $fileext = 'flv';
					else $fileext = 'do';
					echo $attachment->uploadedfiles[0]['filename'].','.$this->upload_url.$attachment->uploadedfiles[0]['filepath'].','.$attachment->uploadedfiles[0]['filesize'].','.$attachment->uploadedfiles[0]['fileext'].','.$fileext;
				}
				NLOG::log('    [attachment]'.$attachment->uploadedfiles[0]['filename'].','.$this->upload_url.$attachment->uploadedfiles[0]['filepath'].','.$attachment->uploadedfiles[0]['filesize'].','.$attachment->uploadedfiles[0]['fileext'].','.$attachment->uploadedfiles[0]['fileext']);
				exit;
			}
			else
			{
				echo '0,'.$attachment->error();
				exit;
			}
		}
		else
		{
			$this->_app->showmessage('300','上传失败！');
		}
	}
}
?>