<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="<?php echo $this->_context->url('category::add@content'); ?>" target="dialog" mask="true" maxable="false" rel="content_category_add" width="800" height="560"><span>添加栏目</span></a></li>
      <li><a class="add" href="<?php echo $this->_context->url('category::add@content','type/1'); ?>" target="dialog" mask="true" maxable="false" rel="content_category_add" width="800" height="560"><span>添加单网页</span></a></li>
      <li><a class="add" href="<?php echo $this->_context->url('category::add@content','type/2'); ?>" target="dialog" mask="true" maxable="false" rel="content_category_add" width="800" height="560"><span>添加外部链接</span></a></li>
    </ul>
  </div>
  <table class="table" width="100%" layoutH="82">
    <thead>
      <tr>
        <th align="center" width="40">排序</th>
        <th width="60" align="center">catid</th>
        <th>栏目名称</th>
        <th align="center">栏目类型</th>
        <th align="center">数据量</th>
        <th align="center">访问</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php echo $categorys;?>
    </tbody>
  </table>
</div>