<div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="{url 'model::add@content'}" target="dialog" mask="true" maxable="false" rel="content_model_add" width="800" height="400"><span>添加模型</span></a></li>
    </ul>
  </div>
  <table class="table" width="100%" layoutH="82">
    <thead>
      <tr>
        <th width="60" align="center">modelid</th>
        <th>模型名称</th>
        <th>数据表</th>
        <th align="center">状态</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      {loop $data $r}
      <tr>
        <td>{$r['modelid']}</td>
        <td>{$r['name']}</td>
        <td>{$r['tablename']}</td>
        <td align="center">{php echo $r['disabled'] ? '<font color="blue">√</font>' : '<font color="red">×</font>'}</td>
        <td align="center">
          管理字段 | 修改 | 删除
        </td>
      </tr>
      {/loop}
    </tbody>
  </table>
  <div class="panelBar">
  {$pages}
  </div>
</div>