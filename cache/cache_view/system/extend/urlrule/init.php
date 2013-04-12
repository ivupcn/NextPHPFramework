<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <div class="panelBar">
    <ul class="toolBar">
      <li><a class="add" href="<?php echo $this->_context->url('urlrule::add@extend'); ?>" target="dialog" mask="true" maxable="false" rel="extend_urlrule_add" width="800" height="400"><span>添加 URL 规则</span></a></li>
    </ul>
  </div>
  <form id="pagerForm" action="<?php echo $this->_context->url('urlrule::init@extend'); ?>" method="post">
  <input type="hidden" name="pageNum" value="<?php echo $page;?>" />
    <table class="table" width="100%" layoutH="82" rel="jbsxBox">
    <thead>
      <tr>
        <th width="40" align="center">ID</th>
        <th>所属模块</th>
        <th>名称</th>
        <th>是否生成静态？</th>
        <th>URL 规则</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td><?php echo $r['urlruleid'];?></td>
        <td><?php echo $r['module'];?></td>
        <td><?php echo $r['file'];?></td>
        <td align="center"><?php echo $r['ishtml'] ? '<font color="red">√</font>' : '<font color="blue">×</font>'?></td>
        <td><?php echo $r['urlrule'];?></td>
        <td><a href="<?php echo $this->_context->url('urlrule::edit@extend','urlruleid/'.$r['urlruleid']); ?>" target="dialog" mask="true" maxable="false" rel="extend_urlrule_edit" width="800" height="400">修改</a> | <a href="?m=extend&c=urlrule&a=delete&urlruleid=<?php echo $r['urlruleid'];?>" target="ajaxTodo" title="确定要删除吗?">删除</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="panelBar">
  <?php echo $pages;?>
  </div>
  </form>
</div>