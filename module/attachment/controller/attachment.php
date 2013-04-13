<?php
class attachment_controller_attachment extends attachment_class_controller
{
	function action_init()
	{
		die('非法参数！');
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
				$grouplist = getcache('grouplist_'.ROUTE_S,'user');
				$groupallowattachment = $grouplist[$_SESSION['groupid']]['allowattachment'];
			}
			if($_POST['swf_auth_key'] != md5(Next::config('system','auth_key','29HTvKg84Veg8VtDdKbs').$_POST['SWFUPLOADSESSID']) || !$groupallowattachment) exit('该用户组禁止上传附件');
			$site_setting = $this->get_site_setting();
			$alowexts = $site_setting['upload_allowext'];
			$maxsize = $site_setting['upload_maxsize'] * 1024;
			$attachment = new attachment();
			$aids = $attachment->upload('Filedata',$alowexts,$maxsize,$this->upload_path,0);
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
			$this->_app->showmessage('上传失败！');
		}
	}
}
?>