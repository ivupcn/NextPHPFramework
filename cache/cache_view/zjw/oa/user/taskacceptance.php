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
			<?php if(defined('IN_ADMIN')  && !defined('HTML')) {echo "<div class=\"admin_piao\" next_action=\"oa_model_task\" data=\"model=oa_model_task&tag_md5=25657cb5eb843e9335c490e54a5af239&action=listinfo&order=id+DESC&page=%24page\"><a href=\"javascript:void(0)\" class=\"admin_piao_edit\">编辑</a>";}$page = max(intval($page), 1);$offset = 20*($page-1);$data=oa_model_task::model()->select('','*',$offset.',20','id DESC','','');$count = oa_model_task::model()->get_one('', "COUNT(*) AS num");$pages = pages($count["num"], $page, 20);?>
			<table class="table-list" width="100%">
				<thead>
					<tr>
						<th align="center">ID</th>
						<th align="left">任务名称</th>
						<th align="center">截至时间</th>
						<th align="center">指派给</th>
						<th align="center">状态</th>
						<th align="center">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($data)) foreach($data AS $r) { ?>
					<tr>
						<td align="center"><?php echo $r['id'];?></td>
						<td align="left"><a href="<?php echo $this->_context->url('user::task@oa','callback/show/id/'.$r['id']);?>"><?php echo $r['title'];?></a></td>
						<td align="center"><?php echo date('Y-m-d',$r['planendtime']);?></td>
						<td align="center"><?php echo $this->get_userinfo($r['assignedto'],'realname')?></td>
						<td align="center"><?php echo $r['status'];?></td>
						<td align="center"></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="pages"><?php echo $pages;?></div>
			<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>
		</div>
	</div>
</div>
<?php include $this->view('user','index','footer',$this->get_siteid()); ?>