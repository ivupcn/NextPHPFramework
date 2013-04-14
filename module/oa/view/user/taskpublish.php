{view 'user','index','header',SITEID}
<div id="main" class="main">
	<div class="sleeve_main" style="margin-right: 0">
		{view 'user','index','menu',SITEID}
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
									{model:user_model_user action="select" where="siteid = SITEID"}
									{loop $data $r}
									<option value="{$r['userid']}">{$r['realname']}</option>
									{/loop}
									{/model}
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
								<?php echo form::select_category('category_oa_'.SITEID,0,'name="info[tagid]" id="tagid" class="shadowfield required"','',0,-1);?>
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
{view 'user','index','footer',SITEID}