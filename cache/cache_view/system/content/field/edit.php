<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="<?php echo $this->_context->url('field::edit@content'); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="170">字段类型：</th>
          <td>
            <input type="hidden" name="info[formtype]" value="<?php echo $formtype;?>" />
            <?php echo form::select($fields,$formtype,'disabled','请选择字段类型');?>
          </td>
        </tr>
        <tr>
          <th>字段名：</th>
          <td><input type="text" name="info[field]" id="field" size="20" class="required lettersonly input-text" value="<?php echo $field;?>"<?php if(in_array($field,$forbid_delete)) { ?> readonly="true"<?php } ?> /></td>
        </tr>
        <tr>
          <th>字段别名：</th>
          <td><input type="text" name="info[name]" id="field" size="20" class="required input-text" value="<?php echo $name;?>" /></td>
        </tr>
        <tr>
          <th>字段提示：</th>
          <td><input type="text" name="info[tips]" id="field" size="70" class="input-text" value="<?php echo htmlspecialchars($tips);?>" /></td>
        </tr>
        <tr>
          <th>相关参数：</th>
          <td><?php echo $form_data;?></td>
        </tr>
        <tr id="formattribute">
          <th>表单附加属性：</th>
          <td><input type="text" name="info[formattribute]" value="" size="70" class="input-text" value="<?php echo htmlspecialchars($formattribute);?>" /></td>
        </tr>
        <tr id="css">
          <th>表单样式名：</th>
          <td><input type="text" name="info[css]" value="" size="20" class="input-text" value="<?php echo htmlspecialchars($css);?>" /></td>
        </tr>
        <tr>
          <th>字符长度取值范围：</th>
          <td><label>最小值：<input type="text" name="info[minlength]" id="field_minlength" value="<?php echo $minlength;?>" size="5" class="input-text" style="float:right" /></label><label>　最大值：<input type="text" name="info[maxlength]" id="field_maxlength" value="<?php echo $maxlength;?>" size="5" class="input-text" style="float:right" /></label></td>
        </tr>
        <tr>
          <th>数据校验正则：</th>
          <td>
            <input type="text" name="info[pattern]" id="pattern" value="" size="40" class="input-text" value="<?php echo $pattern;?>" /> 
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
          <td><input type="text" name="info[errortips]" value="" size="70" class="input-text" value="<?php echo htmlspecialchars($errortips);?>" /></td>
        </tr>
        <tr>
          <th>值唯一：</th>
          <td><label><input type="radio" name="info[isunique]" value="1" id="field_allow_isunique1" <?php if($isunique) { ?>checked<?php } ?> <?php if(!$field_allow_isunique) { ?>disabled<?php } ?> />是</label><label><input type="radio" name="info[isunique]" value="0" id="field_allow_isunique0" <?php if(!$isunique) { ?>checked<?php } ?> <?php if(!$field_allow_isunique) { ?>disabled<?php } ?> />否</label></td>
        </tr>
        <tr>
          <th>作为搜索条件：</th>
          <td><label><input type="radio" name="info[issearch]" value="1" id="field_allow_search1" <?php if($issearch) { ?>checked<?php } ?> <?php if(!$field_allow_search) { ?>disabled<?php } ?> />是</label><label><input type="radio" name="info[issearch]" value="0" id="field_allow_search0" <?php if(!$issearch) { ?>checked<?php } ?> <?php if(!$field_allow_search) { ?>disabled<?php } ?> />否</label></td>
        </tr>
        <tr>
          <th>在前台投稿中显示：</th>
          <td><label><input type="radio" name="info[isadd]" value="1" <?php if($isadd) { ?>checked<?php } ?> />是</label><label><input type="radio" name="info[isadd]" value="0" <?php if(!$isadd) { ?>checked<?php } ?> />否</label></td>
        </tr>
        <tr>
          <th>作为全站搜索信息：</th>
          <td><label><input type="radio" name="info[isfulltext]" value="1" id="field_allow_fulltext1" <?php if($isfulltext) { ?>checked<?php } ?> <?php if(!$field_allow_fulltext) { ?>disabled<?php } ?> />是</label><label><input type="radio" name="info[isfulltext]" value="0" id="field_allow_fulltext0" <?php if(!$isfulltext) { ?>checked<?php } ?> <?php if(!$field_allow_fulltext) { ?>disabled<?php } ?> />否</label></td>
        </tr>
        <tr>
          <th>在推荐位标签中调用：</th>
          <td><label><input type="radio" name="info[isposition]" value="1" <?php if($isposition) { ?>checked<?php } ?> />是</label><label><input type="radio" name="info[isposition]" value="0" <?php if(!$isposition) { ?>checked<?php } ?> />否</label></td>
        </tr>
      </table>
      <input name="info[modelid]" type="hidden" value="<?php echo $modelid;?>">
      <input name="fieldid" type="hidden" value="<?php echo $fieldid;?>">
      <input name="oldfield" type="hidden" value="<?php echo $field;?>">
    </div>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>