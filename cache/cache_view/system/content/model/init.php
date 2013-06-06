<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="<?php echo $this->_context->url('model::add@content'); ?>" target="dialog" mask="true" maxable="false" rel="content_model_add" width="800" height="260"><span>添加模型</span></a></li>
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
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td><?php echo $r['modelid'];?></td>
        <td><?php echo $r['name'];?></td>
        <td><?php echo $r['tablename'];?></td>
        <td align="center"><?php echo $r['disabled'] ? '<font color="red">×</font>' : '<font color="blue">√</font>'?></td>
        <td align="center">
          <a href="<?php echo $this->_context->url('field::priview@content','modelid/'.$r['modelid']); ?>" target="dialog" mask="true" maxable="false" rel="content_field_priview" width="1000" height="600">预览模型</a> | <a href="<?php echo $this->_context->url('field::init@content','modelid/'.$r['modelid']); ?>" target="navTab" rel="content_field_init" title="<?php echo $r['name'];?>字段管理">字段管理</a> | <a href="<?php echo $this->_context->url('model::edit@content','modelid/'.$r['modelid']); ?>" target="dialog" mask="true" maxable="false" rel="content_model_add" width="800" height="260">修改</a> | <a href="<?php echo $this->_context->url('model::delete@content','modelid/'.$r['modelid']); ?>" target="ajaxTodo" title="确定要删除吗?">删除</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="panelBar">
  <?php echo $pages;?>
  </div>
</div>