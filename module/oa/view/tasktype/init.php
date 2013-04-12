<div class="pageContent">
<form action="?m=oa&c=tasktype&a=listorder" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="?m=oa&c=tasktype&a=add" target="dialog" mask="true" maxable="false" rel="oa_tasktype_add" width="800" height="400"><span>添加任务类型</span></a></li>
    </ul>
  </div>
  <table class="table" width="100%" layoutH="92">
    <thead>
      <tr>
        <th width="40" align="center">排序</th>
        <th width="40" align="center">ID</th>
        <th>任务类型名称</th>
        <th align="center">工作流</th>
        <th align="center">考评分值</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      {$tags}
    </tbody>
  </table>
  <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">排序</button></div></div></li>
      </ul>
    </div>
</form>
</div>