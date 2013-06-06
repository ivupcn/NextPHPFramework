<div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="{url 'model::add@content'}" target="dialog" mask="true" maxable="false" rel="content_model_add" width="800" height="260"><span>添加模型</span></a></li>
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
        <td align="center">{php echo $r['disabled'] ? '<font color="red">×</font>' : '<font color="blue">√</font>'}</td>
        <td align="center">
          <a href="{url 'field::priview@content','modelid/'.$r['modelid']}" target="dialog" mask="true" maxable="false" rel="content_field_priview" width="1000" height="600">预览模型</a> | <a href="{url 'field::init@content','modelid/'.$r['modelid']}" target="navTab" rel="content_field_init" title="{$r['name']}字段管理">字段管理</a> | <a href="{url 'model::edit@content','modelid/'.$r['modelid']}" target="dialog" mask="true" maxable="false" rel="content_model_add" width="800" height="260">修改</a> | <a href="{url 'model::delete@content','modelid/'.$r['modelid']}" target="ajaxTodo" title="确定要删除吗?">删除</a>
        </td>
      </tr>
      {/loop}
    </tbody>
  </table>
  <div class="panelBar">
  {$pages}
  </div>
</div>