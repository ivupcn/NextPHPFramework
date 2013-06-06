<div class="pageContent">
	<div class="pad_10">
		<div class="pageFormContent" layoutH="52">
			<input id="publishatt" type="file" name="info[attachment]" value="" uploaderOption="{formData:{swf_auth_key:'<?php echo $swf_auth_key;?>',SWFUPLOADSESSID:'<?php echo $SWFUPLOADSESSID;?>',module:'<?php echo $module;?>',catid:<?php echo $catid;?>,ajax:1},buttonText:'请选择文件',queueSizeLimit:<?php echo $queueSizeLimit;?>,fileSizeLimit:'<?php echo $fileSizeLimit;?>KB',fileTypeExts:'<?php echo $fileTypeExts;?>',auto:true,multi:true,onUploadSuccess:uploadifySuccessBringBack,onQueueComplete:uploadifyQueueComplete}" />
		</div>
		<div class="formBar">
		  <ul>
		    <li><div class="buttonActive"><div class="buttonContent"><button class="bringBackUploadifyInput" rel="attachment_input2">批量保存</button></div></div></li>
		    <li><div class="buttonActive"><div class="buttonContent"><button class="bringBackUploadifySrc" rel="logo_input">保存</button></div></div></li>
		    <li><div class="button buttonHover"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
		  </ul>
		</div>
	</div>
</div>