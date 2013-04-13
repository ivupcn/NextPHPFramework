<div class="pageContent" id="tabletree">
<form action="?m=user&c=acl&a=setting" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
<table class="treetable" width="100%" layoutH="35">
    <thead>
        <tr>
            <th>菜单名称</th>
            <th>M</th>
            <th>C</th>
            <th>A</th>
            <th>角色</th>
        </tr>
    </thead>
<tbody>
{$categorys}
</tbody>
</table>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</form>
</div>