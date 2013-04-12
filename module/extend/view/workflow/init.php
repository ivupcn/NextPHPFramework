<div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="{url 'workflow::add@extend'}" target="dialog" mask="true" maxable="false" rel="extend_workflow_add" width="800" height="400"><span>添加工作流</span></a></li>
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
      {loop $data $r}
      <tr>
        <td>{$r['workflowid']}</td>
        <td>{$r['workname']}</td>
        <td><a href="{url 'workflow::view@extend','workflowid/'.$r['workflowid']}" target="dialog" mask="true" maxable="false" rel="extend_workflow_view" width="800" height="400">点击查看</a></td>
        <td>{$r['description']}</td>
        <td><a href="{url 'workflow::edit@extend','workflowid/'.$r['workflowid']}" target="dialog" mask="true" maxable="false" rel="extend_workflow_edit" width="800" height="400">修改</a> | <a href="{url 'workflow::delete@extend','workflowid/'.$r['workflowid']}" target="ajaxTodo" title="确定要删除吗?">删除</a></td>
      </tr>
      {/loop}
    </tbody>
  </table>
</div>