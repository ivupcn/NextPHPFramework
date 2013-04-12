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
			<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"user_model_user\" data=\"model=user_model_user&tag_md5=02514983aba159a004da7ccc2c19ca13&action=select&where=siteid+%3D+%24siteid\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$data=user_model_user::model()->select("siteid = $siteid",'*','','','','');?>
			<table class="table-list" width="100%">
				<thead>
					<tr>
						<th align="center">姓名</th>
						<th align="left">月份</th>
						<th align="center">网站建设</th>
						<th align="center">广告设计</th>
						<th align="center">新闻采访</th>
						<th align="center">ITV维护</th>
						<th align="center">论坛维护</th>
						<th>三维全景</th>
						<th>软件开发</th>
						<th>总分</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($data)) foreach($data AS $r) { ?>
					<tr>
						<td align="center"><?php echo $this->get_userinfo($r['userid'],'realname')?></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>