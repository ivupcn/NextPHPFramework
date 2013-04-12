<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent" style="background:#EEF4F5">
	<div class="panelBar">
	    <ul class="toolBar">
	      <li><a class="add" href="<?php echo $this->_context->url('site::add@admin'); ?>" target="dialog" mask="true" maxable="false" rel="admin_site_add" width="700" height="400"><span>添加站点</span></a></li>
	    </ul>
  	</div>
	<div layoutH="26" style="float:left; display:block; overflow:auto; width:240px; border:solid #CCC; border-width:0 1px 0 0; line-height:21px; background:#fff">
		<ul class="tree treeFolder">
			<li><a href="javascript">X Management Platform</a>
				<ul>
					<?php if(is_array($list)) foreach($list AS $v) { ?>
					<li><a href="<?php echo $this->_context->url('site::edit@admin','siteid/'.$v['siteid']); ?>" target="ajax" rel="siteBox"><?php echo $v['name'];?></a></li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</div>
	<div id="siteBox" class="unitBox" style="margin-left:243px;"></div>
</div>