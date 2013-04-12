<?php defined('IN_Next') or exit('No permission resources.'); ?><div class="pageContent">
  <form action="?m=extend&c=workflow&a=edit" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="150">工作流名称：</th>
          <td><input type="text" name="info[workname]" class="required input-text" alt="请输入工作流名称" value="<?php echo $workname;?>" /></td>
        </tr>
        <tr>
          <th>描述：</th>
          <td><textarea name="info[description]" maxlength="255" style="width:100%;height:60px;"><?php echo $description;?></textarea></td>
        </tr>
        <tr>
          <th>审核级数：</th>
          <td>
            <select name="info[steps]" onchange="select_steps(this.value)">
            <option value='1'<?php if($steps==1) echo ' selected';?>>一级审核</option>
            <option value='2'<?php if($steps==2) echo ' selected';?>>二级审核</option>
            <option value='3'<?php if($steps==3) echo ' selected';?>>三级审核</option>
            <option value='4'<?php if($steps==4) echo ' selected';?>>四级审核</option>
            <option value='5'<?php if($steps==5) echo ' selected';?>>五级审核</option>
            </select>
          </td>
        </tr>
        <tr id="step1">
          <th>一级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,$checkadmin1,'name="checkadmin1[]"','',80);?>
          </td>
        </tr>
        <tr id="step2" style="display:<?php if($steps<2) echo 'none';?>">
          <th>二级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,$checkadmin2,'name="checkadmin2[]"','',80);?>
          </td>
        </tr>
        <tr id="step3" style="display:<?php if($steps<3) echo 'none';?>">
          <th>三级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,$checkadmin3,'name="checkadmin3[]"','',80);?>
          </td>
        </tr>
        <tr id="step4" style="display:<?php if($steps<4) echo 'none';?>">
          <th>四级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,$checkadmin4,'name="checkadmin4[]"','',80);?>
          </td>
        </tr>
        <tr id="step5" style="display:<?php if($steps<5) echo 'none';?>">
          <th>五级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,$checkadmin5,'name="checkadmin5[]"','',80);?>
          </td>
        </tr>
        <tr>
          <th><strong>免审核人员：</strong></th>
          <td>
          <?php echo form::checkbox($admin_data,$nocheck_users,'name="nocheck_users[]"','',80);?>
        </td>
        </tr>
        <tr>
          <th>审核状态时是否允许修改：</th>
          <td>
          <label><input type="radio" name="info[flag]" value="1"<?php if($flag) echo ' checked';?> /> 是</label> 
          <label><input type="radio" name="info[flag]" value="0"<?php if(!$flag) echo ' checked';?> /> 否</label>
        </td>
        </tr>
      </table>
      <input type="hidden" name="workflowid" value="<?php echo $workflowid?>"/>
      <SCRIPT LANGUAGE="JavaScript">
      <!--
      function select_steps(stepsid) {
        for(i=5;i>1;i--) {
          if(stepsid>=i) {
            $('#step'+i).css('display','');
          } else {
            $('#step'+i).css('display','none');
          }
        }
      }
      //-->
      </SCRIPT>
    </div>
    <div class="formBar">
      <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
      </ul>
    </div>
  </form>
</div>