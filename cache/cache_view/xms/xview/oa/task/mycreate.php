<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form id="pagerForm" action="?m=oa&c=task&a=mycreate" method="post">
  <input type="hidden" name="pageNum" value="<?php echo $page;?>" />
  <table class="table" width="100%" layoutH="56" rel="alllist">
    <thead>
      <tr>
        <th width="40" align="center">ID</th>
        <th>任务名称</th>
        <th>任务类型</th>
        <th align="center">指派给</th>
        <th align="center">计划开始时间</th>
        <th align="center">计划结束时间</th>
        <th align="center">分值</th>
        <th align="center">自评</th>
        <th align="center">考评</th>
        <th align="center">状态</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td align="center"><?php echo $r['id'];?></td>
        <td>
          <?php echo $r['title'];?>
          <?php if($r['intro']) { ?>　[ <a href="?m=oa&c=task&a=intro&id=<?php echo $r['id'];?>" target="dialog" mask="true" maxable="false" rel="oa_task_intro" width="500" height="300">查看备注</a> ]<?php } ?>
        </td>
        <td>
          <?php echo $category[$r['tagid']]['tagname'];?>
        </td>
        <td align="center"><?php echo $userop->get_userinfo($r['assignedto'],'realname');?></td>
        <td align="center"><?php echo date('Y-m-d',$r['planstarttime']);?></td>
        <td align="center"><?php echo date('Y-m-d',$r['planendtime']);?></td>
        <td align="center">
          <?php $setting = string2array($category[$r['tagid']]['setting'])?>
          <?php echo $setting['presentpoint'];?>
        </td>
        <td align="center"><?php echo $r['selfrated'];?></td>
        <td align="center"><?php echo $r['compentedrated'];?></td>
        <td align="center">
          <?php if($r['status'] == 1) { ?>
          <font color="red">尚未进行</font>
          <?php } elseif ($r['status'] == 2) { ?>
          <font color="#009900">正在进行</font>
          <?php } elseif ($r['status'] == 3) { ?>
          <font color="red">验收未过</font>
          <?php } elseif ($r['status'] == 4) { ?>
          等待验收
          <?php } elseif ($r['status'] == 99) { ?>
          <font color="blue">任务完成</font>
          <?php } ?>
        </td>
        <td align="center">
          <?php $workflowid = $category[$r['tagid']]['workflowid']?>
          <?php $flag = $workflow[$workflowid]['flag']?>
          <?php if($r['status'] != 99 && ($flag || $r['status'] < 4)) { ?><a href="?m=oa&c=task&a=edit&id=<?php echo $r['id'];?>" target="dialog" mask="true" maxable="false" rel="oa_task_edit" width="800" height="500">修改</a><?php } else { ?><font color="#cccccc">修改</font><?php } ?>
           | 
          <?php if($r['status'] < 4) { ?><a href="?m=oa&c=task&a=delete&id=<?php echo $r['id'];?>" target="ajaxTodo" title="确定要删除吗?">删除</a><?php } else { ?><font color="#cccccc">删除</font><?php } ?>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="panelBar">
  <?php echo $pages;?>
  </div>
  </form>
</div>