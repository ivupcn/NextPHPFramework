<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form id="pagerForm" action="?m=oa&c=task&a=checklist" method="post">
  <input type="hidden" name="pageNum" value="<?php echo $page;?>" />
  <table class="table" width="100%" layoutH="56" rel="alllist">
    <thead>
      <tr>
        <th width="40" align="center">ID</th>
        <th>任务名称</th>
        <th align="center">任务类型</th>
        <th align="center">指派给</th>
        <th align="center">审核阶段</th>
        <th align="center">管理操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($data)) foreach($data AS $r) { ?>
      <tr>
        <td align="center"><?php echo $r['fromid'];?></td>
        <td><?php echo $r['title'];?></td>
        <td><?php echo $category[$r['tagid']]['tagname'];?></td>
        <td align="center"><?php echo $userop->get_userinfo($r['userid'],'realname');?></td>
        <td align="center"><?php if($r['steps'] == 1) { ?>一审<?php } elseif ($r['steps'] == 2) { ?>二审<?php } elseif ($r['steps'] == 3) { ?>三审<?php } elseif ($r['steps'] == 4) { ?>四审<?php } elseif ($r['steps'] == 5) { ?>五审<?php } ?></td>
        <td align="center">
          <?php
          $setting = string2array($category[$r['tagid']]['setting']);
          if(isset($setting['workflowid']) && !empty($setting['workflowid']))
          {
            $workflow = $workflows[$setting['workflowid']];
            $workflow['setting'] = string2array($workflow['setting']);
            foreach($workflow['setting'] as $k=>$v)
            {   
                if($k == 'nocheck_users' || !$v)
                {
                  continue;
                }
                if(in_array($_SESSION['userid'], $v) || $_SESSION['roleid'] == 1)
                {
                  echo '<a href="?m=oa&c=task&a=check&id='.$r['fromid'].'" target="dialog" mask="true" maxable="false" rel="oa_task_check" width="800" height="500">验收</a>';
                }
            } 
          }
          ?>
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