<div class="pageContent">
  <form id="pagerForm" action="?m=oa&c=task&a=checklist" method="post">
  <input type="hidden" name="pageNum" value="{$page}" />
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
      {loop $data $r}
      <tr>
        <td align="center">{$r['fromid']}</td>
        <td>{$r['title']}</td>
        <td>{$category[$r['tagid']]['tagname']}</td>
        <td align="center"><?php echo $userop->get_userinfo($r['userid'],'realname');?></td>
        <td align="center">{if $r['steps'] == 1}一审{elseif $r['steps'] == 2}二审{elseif $r['steps'] == 3}三审{elseif $r['steps'] == 4}四审{elseif $r['steps'] == 5}五审{/if}</td>
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
      {/loop}
    </tbody>
  </table>
  <div class="panelBar">
  {$pages}
  </div>
  </form>
</div>