<div class="pageContent">
  <form id="pagerForm" action="?m=oa&c=task&a=my" method="post">
  <input type="hidden" name="pageNum" value="{$page}" />
  <table class="table" width="100%" layoutH="56" rel="alllist">
    <thead>
      <tr>
        <th width="40" align="center">ID</th>
        <th>任务名称</th>
        <th>任务类型</th>
        <th align="center">创建者</th>
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
      {loop $data $r}
      <tr>
        <td align="center">{$r['id']}</td>
        <td>
          {$r['title']}
          {if $r['intro']}　[ <a href="?m=oa&c=task&a=intro&id={$r['id']}" target="dialog" mask="true" maxable="false" rel="oa_task_intro" width="500" height="300">查看备注</a> ]{/if}
        </td>
        <td>
          {$category[$r['tagid']]['tagname']}
        </td>
        <td align="center"><?php echo $userop->get_userinfo($r['sponsor'],'realname');?></td>
        <td align="center">{date('Y-m-d',$r['planstarttime'])}</td>
        <td align="center">{date('Y-m-d',$r['planendtime'])}</td>
        <td align="center">
          {php $setting = string2array($category[$r['tagid']]['setting'])}
          {$setting['presentpoint']}
        </td>
        <td align="center">{$r['selfrated']}</td>
        <td align="center">{$r['compentedrated']}</td>
        <td align="center">
          {if $r['status'] == 1}
          <font color="red">尚未进行</font>
          {elseif $r['status'] == 2}
          <font color="#009900">正在进行</font>
          {elseif $r['status'] == 3}
          <font color="red">验收未过</font>
          {elseif $r['status'] == 4}
          等待验收
          {elseif $r['status'] == 99}
          <font color="blue">任务完成</font>
          {/if}
        </td>
        <td align="center">
          {if $r['status'] == 1}<a href="?m=oa&c=task&a=accepted&id={$r['id']}" target="ajaxTodo" title="确定要领取任务吗?">接受</a>{else}<font color="#cccccc">接受</font>{/if}
           | 
          {if $r['status'] > 1 && $r['status'] < 4}<a href="?m=oa&c=task&a=progress&id={$r['id']}" target="dialog" mask="true" maxable="false" rel="oa_task_progress" width="800" height="300">进度</a>{else}<font color="#cccccc">进度</font>{/if}
           | 
          {if $r['status'] > 1 && $r['status'] < 4}<a href="?m=oa&c=task&a=complete&id={$r['id']}" target="dialog" mask="true" maxable="false" rel="oa_task_complete" width="800" height="500">完成</a>{else}<font color="#cccccc">完成</font>{/if}
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