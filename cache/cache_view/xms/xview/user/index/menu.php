<?php defined('IN_Next') or exit('No permission resources.'); ?><?php if(isset($_GET['menuid']) && intval($_GET['menuid'])) { ?>
<div class="nav_bar">
	<div class="nav_box">
		<ul class="sort sort_profile S_line2 clearfix">
			<?php $sec_menu_arr = $this->user_menu($_GET['menuid']);?>
			<?php if(is_array($sec_menu_arr)) foreach($sec_menu_arr AS $_value) { ?>
			<li class="item<?php if(ROUTE_A == $_value['a']) { ?> current<?php } ?>"><a class="item_link S_func1" href="<?php echo $this->_context->url($_value['c'].'::'.$_value['a'].'@'.$_value['m'],'menuid/'.$_GET['menuid']);?>"><?php echo $_value['name'];?></a><div class="W_tabarrow S_bg4"></div></li>
			<?php } ?>
		</ul>
		<div class="right_bar">
			<div class="input_bar">
				<div class="inner">
					<div class="btns">
						<a suda-data="key=tblog_otherprofile_v4&amp;value=search_magnifier" class="btn_search W_ico20 iconsearch" href="javascript:void(0);" action-type="search_key"></a>
					</div>
					<form method="get" action="" node-type="singleForm">
						<input class="noborder input_default" node-type="keyword" type="text" name="key_word" value="" />
						<a action-type="search_key" href="javascript:void();" class="btn" onclick="return false;"></a>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php if(isset($callback)) { ?>
	<div class="tab_nosep">
		<ul class="t_ul">
			<li class="t_itm<?php if($callback == 'assignedto') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/assignedto');?>">指派给我的任务</a></li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php } ?>