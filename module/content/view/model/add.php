<div class="pageContent">
  <form action="{url 'model::add@content'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="100">模型名称：</th>
          <td><input type="text" name="info[name]" class="required input-text" alt="请输入模型名称" /></td>
        </tr>
        <tr>
          <th>模型表键名：</th>
          <td><input type="text" name="info[tablename]" class="required input-text" alt="请输入模型表键名" /></td>
        </tr>
        <tr>
          <th>描述：</th>
          <td><input type="text" name="info[description]" class="input-text" size="70" /></td>
        </tr>
        <tr>
          <th>内容页模板：</th>
          <td><input type="text" name="info[show_view]" class="input-text" size="50" /></td>
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