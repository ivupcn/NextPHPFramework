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
			<h1>TASK #<?php echo $taskinfo['id'];?>　<?php echo $taskinfo['title'];?></h1>
			<div id="content-sidebar">
				<fieldset>
					<legend>基本信息</legend>
					<table class="table-info border-none" width="100%">
						<tbody>
							<tr>
								<th style="text-align:left">任务类型：</th>
								<td><?php echo $taskinfo['tagid'];?></td>
							</tr>
							<tr>
								<th style="text-align:left">任务分值：</th>
								<td></td>
							</tr>
							<tr>
								<th style="text-align:left">创 建 者 ：</th>
								<td><?php echo $this->get_userinfo($taskinfo['sponsor'],'realname')?></td>
							</tr>
							<tr>
								<th style="text-align:left">指 派 给 ：</th>
								<td><?php echo $this->get_userinfo($taskinfo['assignedto'],'realname')?></td>
							</tr>
							<tr>
								<th style="text-align:left">任务状态：</th>
								<td><?php echo $taskinfo['status'];?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>工时信息</legend>
					<table class="table-info border-none" width="100%">
						<tbody>
							<tr>
								<th style="text-align:left">预计开始时间：</th>
								<td><?php echo date('Y-m-d',$taskinfo['planstarttime']);?></td>
							</tr>
							<tr>
								<th style="text-align:left">实际开始时间：</th>
								<td><?php if(isset($taskinfo['actualstarttime'])) { ?><?php echo date('Y-m-d',$taskinfo['actualstarttime']);?><?php } ?></td>
							</tr>
							<tr>
								<th style="text-align:left">预计结束时间：</th>
								<td><?php echo date('Y-m-d',$taskinfo['planendtime']);?></td>
							</tr>
							<tr>
								<th style="text-align:left">实际结束时间：</th>
								<td><?php if(isset($taskinfo['actualendtime'])) { ?><?php echo date('Y-m-d',$taskinfo['actualendtime']);?><?php } ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="content">
				<fieldset>
					<legend>任务描述</legend>
					<div class="text">
						<?php echo $taskinfo['intro'];?>
					</div>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>附　　件</legend>
					<div class="attachment">
						<?php echo $taskinfo['attachment'];?>
					</div>
				</fieldset>
				<div class="bak10"></div>
				<fieldset>
					<legend>考评细则</legend>
					<div class="text">
						<?php echo $taskinfo['tagid'];?>
					</div>
				</fieldset>
			</div>
				
			
			
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>