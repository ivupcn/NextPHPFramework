<div class="pageContent">
  <form action="{url 'field::add@content','modelid/'.$modelid}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="170">字段类型：</th>
          <td>
            <?php echo form::select($all_field,'','class="required" name="info[formtype]" id="formtype" onchange="javascript:field_setting(this.value);"','请选择字段类型');?>
          </td>
        </tr>
        <tr>
          <th>字段名：</th>
          <td><input type="text" name="info[field]" id="field" size="20" class="required lettersonly input-text" /></td>
        </tr>
        <tr>
          <th>字段别名：</th>
          <td><input type="text" name="info[name]" id="field" size="20" class="required input-text" /></td>
        </tr>
        <tr>
          <th>字段提示：</th>
          <td><input type="text" name="info[tips]" id="field" size="70" class="input-text" /></td>
        </tr>
        <tr>
          <th>相关参数：</th>
          <td><div id="setting"></div></td>
        </tr>
        <tr id="formattribute">
          <th>表单附加属性：</th>
          <td><input type="text" name="info[formattribute]" value="" size="70" class="input-text" /></td>
        </tr>
        <tr id="css">
          <th>表单样式名：</th>
          <td><input type="text" name="info[css]" value="" size="20" class="input-text" /></td>
        </tr>
        <tr>
          <th>字符长度取值范围：</th>
          <td><label>最小值：<input type="text" name="info[minlength]" id="field_minlength" value="0" size="5" class="input-text" style="float:right" /></label><label>　最大值：<input type="text" name="info[maxlength]" id="field_maxlength" value="" size="5" class="input-text" style="float:right" /></label></td>
        </tr>
        <tr>
          <th>数据校验正则：</th>
          <td>
            <input type="text" name="info[pattern]" id="pattern" value="" size="40" class="input-text" /> 
            <select name="pattern_select" onchange="javascript:$('#pattern').val(this.value)">
            <option value="">常用正则</option>
            <option value="/^[0-9.-]+$/">数字</option>
            <option value="/^[0-9-]+$/">整数</option>
            <option value="/^[a-z]+$/i">字母</option>
            <option value="/^[0-9a-z]+$/i">数字+字母</option>
            <option value="/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/">E-mail</option>
            <option value="/^[0-9]{5,20}$/">QQ</option>
            <option value="/^http:\/\//">网址</option>
            <option value="/^(1)[0-9]{10}$/">手机号码</option>
            <option value="/^[0-9-]{6,13}$/">电话号码</option>
            <option value="/^[0-9]{6}$/">邮政编码</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>数据校验未通过的提示信息：</th>
          <td><input type="text" name="info[errortips]" value="" size="70" class="input-text" /></td>
        </tr>
        <tr>
          <th>值唯一：</th>
          <td><label><input type="radio" name="info[isunique]" value="1" id="field_allow_isunique1" />是</label><label><input type="radio" name="info[isunique]" value="0" id="field_allow_isunique0" checked />否</label></td>
        </tr>
        <tr>
          <th>作为搜索条件：</th>
          <td><label><input type="radio" name="info[issearch]" value="1" id="field_allow_search1" />是</label><label><input type="radio" name="info[issearch]" value="0" id="field_allow_search0" checked />否</label></td>
        </tr>
        <tr>
          <th>在前台投稿中显示：</th>
          <td><label><input type="radio" name="info[isadd]" value="1" checked />是</label><label><input type="radio" name="info[isadd]" value="0" />否</label></td>
        </tr>
        <tr>
          <th>作为全站搜索信息：</th>
          <td><label><input type="radio" name="info[isfulltext]" value="1" id="field_allow_fulltext1" checked />是</label><label><input type="radio" name="info[isfulltext]" value="0" id="field_allow_fulltext0" />否</label></td>
        </tr>
        <tr>
          <th>在推荐位标签中调用：</th>
          <td><label><input type="radio" name="info[isposition]" value="1" />是</label><label><input type="radio" name="info[isposition]" value="0" checked/>否</label></td>
        </tr>
      </table>
      <input type="hidden" name="info[modelid]" value="{$modelid}">
    </div>
    <script type="text/javascript">
    <!--
    function field_setting(fieldtype){
      $('#formattribute').css('display','none');
      $('#css').css('display','none');
      $.each(['<?php echo implode("','",$att_css_js);?>'], function(i, n){
        if(fieldtype==n) {
          $('#formattribute').css('display','');
          $('#css').css('display','');
        }
      });  
      $.getJSON("<?php echo $this->_context->url('field::setting@content'); ?>&fieldtype="+fieldtype, function(data){
        if(data.field_basic_table=='1') {
          $('#field_basic_table0').attr("disabled",false);
          $('#field_basic_table1').attr("disabled",false);
        } else {
          $('#field_basic_table0').attr("checked",true);
          $('#field_basic_table0').attr("disabled",true);
          $('#field_basic_table1').attr("disabled",true);
        }
        if(data.field_allow_search=='1') {
          $('#field_allow_search0').attr("disabled",false);
          $('#field_allow_search1').attr("disabled",false);
        } else {
          $('#field_allow_search0').attr("checked",true);
          $('#field_allow_search0').attr("disabled",true);
          $('#field_allow_search1').attr("disabled",true);
        }
        if(data.field_allow_fulltext=='1') {
          $('#field_allow_fulltext0').attr("disabled",false);
          $('#field_allow_fulltext1').attr("disabled",false);
        } else {
          $('#field_allow_fulltext0').attr("checked",true);
          $('#field_allow_fulltext0').attr("disabled",true);
          $('#field_allow_fulltext1').attr("disabled",true);
        }
        if(data.field_allow_isunique=='1') {
          $('#field_allow_isunique0').attr("disabled",false);
          $('#field_allow_isunique1').attr("disabled",false);
        } else {
          $('#field_allow_isunique0').attr("checked",true);
          $('#field_allow_isunique0').attr("disabled",true);
          $('#field_allow_isunique1').attr("disabled",true);
        }
        $('#field_minlength').val(data.field_minlength);
        $('#field_maxlength').val(data.field_maxlength);
        $('#setting').html(data.setting);
    
      });
    }
  //-->
  </script>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>