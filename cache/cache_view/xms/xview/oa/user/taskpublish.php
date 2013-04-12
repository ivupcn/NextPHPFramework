<?php defined('IN_Next') or exit('No permission resources.'); ?><?php include $this->view('user','index','header',$this->get_siteid()); ?>
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right: 0">
		<div class="nav_bar">
			<div class="nav_box">
				<ul class="sort sort_profile S_line2 clearfix">
					<li class="item<?php if(ROUTE_A == 'init') { ?> current<?php } ?>"><a class="item_link S_func1" href="<?php echo $this->_context->url('user::init@oa');?>">首页</a><div class="W_tabarrow S_bg4"></div></li>
					<li class="item<?php if(ROUTE_A == 'task') { ?> current<?php } ?>"><a class="item_link S_func1" href="<?php echo $this->_context->url('user::task@oa','callback/all');?>">任务</a><div class="W_tabarrow S_bg4"></div></li>
					<li class="item<?php if(ROUTE_A == 'document') { ?> current<?php } ?>"><a class="item_link S_func1" href="<?php echo $this->_context->url('user::document@oa');?>">文档</a><div class="W_tabarrow S_bg4"></div></li>
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
			<div class="tab_nosep">
				<ul class="t_ul">
					<li class="t_itm<?php if($callback == 'assignedto') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/assignedto');?>">指派给我的任务</a></li>
					<li class="t_itm<?php if($callback == 'sponsor') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/sponsor');?>">由我创建的任务</a></li>
					<li class="t_itm<?php if($callback == 'all') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/all');?>">所有任务</a></li>
					<li class="t_itm<?php if($callback == 'acceptance') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/acceptance');?>">验收任务</a></li>
					<li class="t_itm<?php if($callback == 'assessment') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/assessment');?>">考核任务</a></li>
					<li class="t_itm<?php if($callback == 'publish') { ?> current<?php } ?>"><a class="t_lk" href="<?php echo $this->_context->url('user::task@oa','callback/publish');?>">发布任务</a></li>
				</ul>
			</div>
		</div>
		<div id="post">
			<div class="method clear">
				<div class="inner">
					<div class="tBar">
						<h2>发布任务</h2>
						<p>完成下列表单，发布任务。</p>
					</div>
					<div class="form">
						<script type="text/javascript">
						$(function() {
							$( "#planstarttime" ).datepicker({
								defaultDate: "+1w",
								changeMonth: true,
								numberOfMonths: 1,
								onClose: function( selectedDate ) {
									$( "#planendtime" ).datepicker( "option", "minDate", selectedDate );
								}
							});
							$( "#planendtime" ).datepicker({
								defaultDate: "+1w",
								changeMonth: true,
								numberOfMonths: 1,
								onClose: function( selectedDate ) {
									$( "#planstarttime" ).datepicker( "option", "maxDate", selectedDate );
								}
							});
						});
						</script>
						<form action="<?php echo $this->_context->url('user::task@oa','callback/publish');?>" method="post" class="required-validate">
							<div class="formwrap">
								<label for="assignedto">指 派 给：</label>
								<select name="info[assignedto]" id="assignedto" class="shadowfield">
									<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"user_model_user\" data=\"model=user_model_user&tag_md5=02514983aba159a004da7ccc2c19ca13&action=select&where=siteid+%3D+%24siteid\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$data=user_model_user::model()->select("siteid = $siteid",'*','','','','');?>
									<?php if(is_array($data)) foreach($data AS $r) { ?>
									<option value="<?php echo $r['userid'];?>"><?php echo $r['realname'];?></option>
									<?php } ?>
									<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
								</select>
							</div>
							<div class="formwrap">
								<label for="title">任务名称：</label>
								<input type="text" name="info[title]" id="title" class="shadowfield required" style="width:560px" />
							</div>
							<div class="formwrap">
								<label for="planstarttime">预计开始：</label>
								<input type="text" name="info[planstarttime]" id="planstarttime" class="shadowfield required" />
							</div>
							<div class="formwrap">
								<label for="planendtime">截止日期：</label>
								<input type="text" name="info[planendtime]" id="planendtime" class="shadowfield required" />
							</div>
							<div class="formwrap">
								<label for="assignedto">任务类型：</label>
								<?php echo form::select_category('category_oa_'.$this->get_siteid(),0,'name="info[tagid]" id="tagid" class="shadowfield required"','',0,-1);?>
							</div>
							<div class="formwrap">
								<label for="attachment">附　　件：</label>
								<input type="text" name="info[attachment]" id="attachment" class="shadowfield" />
							</div>
							<div class="formwrap">
								<label for="intro">任务描述：</label>
								<textarea name="info[intro]" id="intro" class="shadowfield" style="width:560px; height:150px"></textarea>
							</div>
							<div class="btn-container">
								<input class="submit" type="submit" class="blu-btn" value="发　布　任　务"/>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>