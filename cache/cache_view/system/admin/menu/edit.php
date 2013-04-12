<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="<?php echo $this->_context->url('menu::edit@admin'); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
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
          <th>菜档名称：</th>
          <td><input type="text" name="info[name]" id="name" class="input-text" value="<?php echo $name;?>" ></td>
        </tr>
        <tr>
          <th>模块名：</th>
          <td><input type="text" name="info[m]" id="m" class="input-text" value="<?php echo $m;?>" ></td>
        </tr>
        <tr>
          <th>文件名：</th>
          <td><input type="text" name="info[c]" id="c" class="input-text" value="<?php echo $c;?>" ></td>
        </tr>
        <tr>
          <th>方法名：</th>
          <td><input type="text" name="info[a]" id="a" class="input-text" value="<?php echo $a;?>" >
          </td>
        </tr>
        <tr>
          <th>附加参数：</th>
          <td><input type="text" name="info[data]" class="input-text" value="<?php echo $data;?>" ></td>
        </tr>
        <tr>
          <th>是否显示菜单：</th>
          <td><label><input type="radio" name="info[display]" value="1" <?php if($display == 1) { ?>checked<?php } ?>> 显示</label><label><input type="radio" name="info[display]" value="0" <?php if($display == 0) { ?>checked<?php } ?>> 隐藏</label></td>
        </tr>
        <tr>
          <th>是否系统：</th>
          <td><label><input type="radio" name="info[sys]" value="0" <?php if($sys == 0) { ?>checked<?php } ?>> 否</label><label><input type="radio" name="info[sys]" value="1" <?php if($sys == 1) { ?>checked<?php } ?>> 是</label></td>
        </tr>
        <tr>
          <th>赋权类型：</th>
          <td><label><input type="radio" name="info[acl_type]" value="0"  <?php if($acl_type == 0) { ?>checked<?php } ?>> 粗放型 + 细分型</label><label><input type="radio" name="info[acl_type]" value="1" <?php if($acl_type == 1) { ?>checked<?php } ?>> 粗放型</label></td>
        </tr>
      </table>
      <input type="hidden" name="id" value="<?php echo $id;?>" />
    </div>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>