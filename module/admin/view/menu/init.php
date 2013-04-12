<div class="pageContent">
<form action="{url 'menu::listorder@admin'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="?m=admin&c=menu&a=add" target="dialog" mask="true" maxable="false" rel="admin_menu_add" width="500" height="400"><span>添加菜单</span></a></li>
    </ul>
  </div>
  <table class="table" width="100%" layoutH="92">
    <thead>
      <tr>
        <th width="40" align="center">排序</th>
        <th width="40" align="center">id</th>
        <th>菜单名称</th>
        <th>M</th>
        <th>C</th>
        <th>A</th>
        <th>Param</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      {$categorys}
    </tbody>
  </table>
  <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">排序</button></div></div></li>
      </ul>
    </div>
</form>
</div>