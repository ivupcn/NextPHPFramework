<div class="pageContent">
  <form action="{url 'workflow::add@extend'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
    <div class="pageFormContent" layoutH="52">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th width="150">工作流名称：</th>
          <td><input type="text" name="info[workname]" class="required input-text" alt="请输入工作流名称" /></td>
        </tr>
        <tr>
          <th>描述：</th>
          <td><textarea name="info[description]" maxlength="255" style="width:100%;height:60px;"></textarea></td>
        </tr>
        <tr>
          <th>审核级数：</th>
          <td>
            <select name="info[steps]" onchange="select_steps(this.value)">
            <option value='1' selected>一级审核</option>
            <option value='2'>二级审核</option>
            <option value='3'>三级审核</option>
            <option value='4'>四级审核</option>
            <option value='5'>五级审核</option>
            </select>
          </td>
        </tr>
        <tr id="step1">
          <th>一级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="checkadmin1[]"','',80);?>
          </td>
        </tr>
        <tr id="step2" style="display:none">
          <th>二级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="checkadmin2[]"','',80);?>
          </td>
        </tr>
        <tr id="step3" style="display:none">
          <th>三级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="checkadmin3[]"','',80);?>
          </td>
        </tr>
        <tr id="step4" style="display:none">
          <th>四级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="checkadmin4[]"','',80);?>
          </td>
        </tr>
        <tr id="step5" style="display:none">
          <th>五级审核 人员列表：</th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="checkadmin5[]"','',80);?>
          </td>
        </tr>
        <tr>
          <th><strong>免审核人员：</strong></th>
          <td>
          <?php echo form::checkbox($admin_data,'','name="nocheck_users[]"','',80);?>
        </td>
        </tr>
        <tr>
          <th>审核状态时是否允许修改：</th>
          <td>
          <label><input type="radio" name="info[flag]" value="1" > 是</label> 
          <label><input type="radio" name="info[flag]" value="0" checked> 否</label>
        </td>
        </tr>
      </table>
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