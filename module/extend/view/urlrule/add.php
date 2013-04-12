<div class="pageContent">
  	<form action="{url 'urlrule::add@extend'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
	    <div class="pageFormContent" layoutH="52">
	      <table width="100%" class="table_form contentWrap">
	      	<tr> 
		      <th width="100">URL 规则名称：</th>
		      <td><input type="text" name="info[file]" id="file" class="required input-text" size="20" alt="请输入 URL 规则名称" /></td>
		    </tr>
		    <tr> 
		      <th width="100">模块名称：</th>
		      <td><?php echo form::select($modules,'content',"name='info[module]' id='module'");?></td>
		    </tr>
		    <tr> 
		      <th width="100">是否生成静态？：</th>
		      <td>
		      	<label></label><input type="radio" value="1" name="info[ishtml]" />是</label>
        		<label></label><input type="radio" value="0" name="info[ishtml]" checked="checked" />否</label>
		      </td>
		    </tr>
		    <tr> 
		      <th width="100">URL 示例：</th>
		      <td><input type="text" name="info[example]" id="example" class="required input-text" size="70" alt="请输入 URL 示例" /></td>
		    </tr>
		    <tr> 
		      <th width="100">URL 规则：</th>
		      <td><input type="text" name="info[urlrule]" id="urlrule" class="required input-text" size="70" alt="请输入 URL 规则" /></td>
		    </tr>
		    <tr> 
		      <th width="100">可用变量：</th>
		      <td>
		      	父栏目路径:{{$categorydir}}<br />
		      	栏目目录：{{$catdir}}<br />
		      	年：{{$year}}<br />
		      	月：{{$month}}<br />
		      	日：{{$day}}<br />
		      	ID：{{$id}}<br />
		      	分页：{{$page}}</td>
		    </tr>
	      </table>
	  	</div>
	  	<div class="formBar">
	      <ul>
	        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
	        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
	      </ul>
	    </div>
	</form>
</div>