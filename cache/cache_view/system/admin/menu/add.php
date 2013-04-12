<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="<?php echo $this->_context->url('menu::add@admin'); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="100">上级菜单：</th>
          <td>
            <select name="info[parentid]" class="combox">
            <option value="0">作为一级菜单</option>
            <?php echo $select_categorys;?>
            </select>
          </td>
        </tr>
        <tr>
          <th>菜单名称：</th>
          <td><input type="text" name="info[name]" class="required input-text" alt="请输入菜单名称" /></td>
        </tr>
        <tr>
          <th>模块名：</th>
          <td><input type="text" name="info[m]" class="required input-text" alt="请输入模块名" /></td>
        </tr>
        <tr>
          <th>文件名：</th>
          <td><input type="text" name="info[c]" id="c" class="input-text" ></td>
        </tr>
        <tr>
          <th>方法名：</th>
          <td><input type="text" name="info[a]" id="a" class="input-text" ></td>
        </tr>
        <tr>
          <th>附加参数：</th>
          <td><input type="text" name="info[data]" class="input-text" ></td>
        </tr>
        <tr>
          <th>是否显示菜单：</th>
          <td><label><input type="radio" name="info[display]" value="1" checked> 显示</label><label><input type="radio" name="info[display]" value="0"> 隐藏</label></td>
        </tr>
        <tr>
          <th>是否系统：</th>
          <td><label><input type="radio" name="info[sys]" value="0" checked> 否</label><label><input type="radio" name="info[sys]" value="1"> 是</label></td>
        </tr>
        <tr>
            <th>赋权类型：</th>
            <td><label><input type="radio" name="info[acl_type]" value="0" checked> 粗放型 + 细分型</label><label><input type="radio" name="info[acl_type]" value="1"> 粗放型</label></td>
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