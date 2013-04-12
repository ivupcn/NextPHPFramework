<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
	<div class="workflow">
		<div class="col <?php if($steps==1) echo 'off';?>">
	    	<div class="content fillet">
	            <div class="title">
	                <div class="fillet">一级审核<span class="line"></span></div>
	            </div>
	            <div class="name"><?php echo $checkadmin1;?></div>
	        </div>
	    </div>
	    <div class="col <?php if($steps==2) echo 'off';?>" style="display:<?php if($steps<2) echo 'none';?>">
	    	<div class="content fillet">
	            <div class="title">
	                <div class="fillet">二级审核<span class="line"></span></div>
	            </div>
	            <div class="name"><?php echo $checkadmin2;?></div>
	        </div>
	    </div>
		<div class="col <?php if($steps==3) echo 'off';?>" style="display:<?php if($steps<3) echo 'none';?>">
	    	<div class="content fillet">
	            <div class="title">
	                <div class="fillet">三级审核<span class="line"></span></div>
	            </div>
	            <div class="name"><?php echo $checkadmin3;?></div>
	        </div>
	    </div>
	    <div class="col <?php if($steps==4) echo 'off';?>" style="display:<?php if($steps<4) echo 'none';?>">
	    	<div class="content fillet">
	            <div class="title">
	                <div class="fillet">四级审核<span class="line"></span></div>
	            </div>
	            <div class="name"><?php echo $checkadmin4;?></div>
	        </div>
	    </div>
	    <div class="col <?php if($steps==5) echo 'off';?>" style="display:<?php if($steps<5) echo 'none';?>">
	    	<div class="content fillet">
	            <div class="title">
	                <div class="fillet">五级审核<span class="line"></span></div>
	            </div>
	            <div class="name"><?php echo $checkadmin5;?></div>
	        </div>
	    </div>
	</div>
</div>