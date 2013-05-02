<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="<?php echo $this->_context->url('field::add@content','modelid/'.$modelid); ?>" target="dialog" mask="true" maxable="false" rel="content_file_add" width="800" height="500"><span>添加字段</span></a></li>
      <li><a class="icon" href="<?php echo $this->_context->url('field::priview@content','modelid/'.$modelid); ?>" target="dialog" mask="true" maxable="false" rel="content_file_priview" width="800" height="260"><span>预览模型</span></a></li>
    </ul>
  </div>
  <form action="<?php echo $this->_context->url('field::listorder@content','modelid/'.$modelid); ?>" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
  <table class="table" width="100%" layoutH="92">
    <thead>
      <tr>
        <th width="60" align="center">排序</th>
        <th>字段名</th>
        <th>别名</th>
        <th>类型</th>
        <th align="center">系统</th>
        <th align="center">必填</th>
        <th align="center">搜索</th>
        <th align="center">投稿</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($base)) foreach($base AS $r) { ?>
      <tr>
        <td>※</td>
        <td><?php echo $r['field'];?></td>
        <td><?php echo $r['name'];?></td>
        <td><?php echo $r['formtype'];?></td>
        <td align="center"><?php echo $r['issystem'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['minlength'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['issearch'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['isadd'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center">
          <a href="<?php echo $this->_context->url('field::edit@content','fieldid/'.$r['fieldid']); ?>" target="dialog" mask="true" maxable="false" rel="content_file_edit" width="800" height="500">修改</a> | 
          <?php if(!in_array($r['field'],$forbid_fields)) { ?>
          <a href="<?php echo $this->_context->url('field::disabled@content','fieldid/'.$r['fieldid'].'/disabled/'.$r['disabled']); ?>" target="ajaxTodo"><?php echo $r['disabled'] ? '启用' : '禁用'?></a> | 
          <?php } else { ?>
          <font color="#BEBEBE">禁用</font> | 
          <?php } ?>
          <?php if(!in_array($r['field'],$forbid_delete)) { ?>
          <a href="<?php echo $this->_context->url('field::delete@content','fieldid/'.$r['fieldid']); ?>" target="ajaxTodo" title="确定要删除吗?">删除</a> | 
          <?php } else { ?>
          <font color="#BEBEBE">删除</font> | 
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td><input name="listorders[<?php echo $r['fieldid'];?>]" type="text" value="<?php echo $r['listorder'];?>" class="input-text-c" style="width:30px" /></td>
        <td><?php echo $r['field'];?></td>
        <td><?php echo $r['name'];?></td>
        <td><?php echo $r['formtype'];?></td>
        <td align="center"><?php echo $r['issystem'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['minlength'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['issearch'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center"><?php echo $r['isadd'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td align="center">
          <a href="<?php echo $this->_context->url('field::edit@content','fieldid/'.$r['fieldid']); ?>" target="dialog" mask="true" maxable="false" rel="content_file_edit" width="800" height="500">修改</a> | 
          <?php if(!in_array($r['field'],$forbid_fields)) { ?>
          <a href="<?php echo $this->_context->url('field::disabled@content','fieldid/'.$r['fieldid'].'/disabled/'.$r['disabled']); ?>" target="ajaxTodo"><?php echo $r['disabled'] ? '启用' : '禁用'?></a> | 
          <?php } else { ?>
          <font color="#BEBEBE">禁用</font> | 
          <?php } ?>
          <?php if(!in_array($r['field'],$forbid_delete)) { ?>
          <a href="<?php echo $this->_context->url('field::delete@content','fieldid/'.$r['fieldid']); ?>" target="ajaxTodo" title="确定要删除吗?">删除</a> | 
          <?php } else { ?>
          <font color="#BEBEBE">删除</font> | 
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="formBar">
    <ul>
      <li><div class="buttonActive"><div class="buttonContent"><button type="submit">排序</button></div></div></li>
    </ul>
  </div>
  </form>
</div>