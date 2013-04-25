<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="<?php echo $this->_context->url('model::edit@content'); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="100">模型名称：</th>
          <td><input type="text" name="info[name]" value="<?php echo $name;?>" class="required input-text" alt="请输入模型名称" /></td>
        </tr>
        <tr>
          <th>模型表键名：</th>
          <td><input type="text" name="info[tablename]" value="tablename" class="required input-text" alt="请输入模型表键名" /></td>
        </tr>
        <tr>
          <th>描述：</th>
          <td><input type="text" name="info[description]" class="input-text" size="70" value="<?php echo $description;?>" /></td>
        </tr>
        <tr>
          <th>内容页模板：</th>
          <td><input type="text" name="info[show_view]" class="input-text" size="50" value="<?php echo $show_view;?>" /></td>
        </tr>
      </table>
      <input type="hidden" name="modelid" value="<?php echo $modelid;?>" />
    </div>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>