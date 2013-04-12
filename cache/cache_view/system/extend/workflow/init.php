<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="?m=extend&c=workflow&a=add" target="dialog" mask="true" maxable="false" rel="extend_workflow_add" width="800" height="400"><span>添加工作流</span></a></li>
    </ul>
  </div>
  <table class="table" width="100%" layoutH="92">
    <thead>
      <tr>
        <th width="40" align="center">ID</th>
        <th>工作流名称</th>
        <th>流程示意图</th>
        <th>描述</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td><?php echo $r['workflowid'];?></td>
        <td><?php echo $r['workname'];?></td>
        <td><a href="?m=extend&c=workflow&a=view&workflowid=<?php echo $r['workflowid'];?>" target="dialog" mask="true" maxable="false" rel="extend_workflow_view" width="800" height="400">点击查看</a></td>
        <td><?php echo $r['description'];?></td>
        <td><a href="?m=extend&c=workflow&a=edit&workflowid=<?php echo $r['workflowid'];?>" target="dialog" mask="true" maxable="false" rel="extend_workflow_edit" width="800" height="400">修改</a> | <a href="?m=extend&c=workflow&a=delete&workflowid=<?php echo $r['workflowid'];?>" target="ajaxTodo" title="确定要删除吗?">删除</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>