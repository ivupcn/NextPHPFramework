<div class="pageContent">
<table class="table" width="100%" layoutH="30">
	<thead>
        <tr>
        <th>当前目录：<?php echo $local?></th>
        </tr>
    </thead>
	<tbody>
	<?php if ($dir !='' && $dir != '.'):?>
	<tr>
		<td><a href="<?php echo $this->_context->url('manage::init@attachment','dir/'.stripslashes(dirname($dir)));?>" target="navTab" title="附件管理" rel="attachment_manage_init"><img src="statics/images/icon/folder-closed.gif" />上一层目录</td>
	</tr>
	<?php endif;?>
	<?php 
	if(is_array($list)):
		foreach($list as $v):
		$filename = basename($v);
	?>
	<tr>
		<?php if (is_dir($v)) {
			echo '<td><img src="statics/images/icon/folder-closed.gif" /> <a href="'.$this->_context->url('manage::init@attachment').'&dir='.(isset($_GET['dir']) && !empty($_GET['dir']) ? stripslashes($_GET['dir']).DIRECTORY_SEPARATOR : '').$filename.'" target="navTab" title="附件管理" rel="attachment_manage_init"><b>'.$filename.'</b></a></td>';
		} else {
			echo '<td> <a href="'.$url.$filename.'" target="_blank" mask="true" maxable="false" rel="attachment_manage_init_view" width="500" height="300">'.$filename.'</a></td>';
		}?>
	</tr>
	<?php 
		endforeach;
	endif;
	?>
	</tbody>
</table>
</div>