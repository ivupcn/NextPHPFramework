<div class="pageContent">
<form action="{url 'site::config@admin'}" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
  <div class="pageFormContent" layoutH="55">
  <fieldset>
  <legend>基本配置</legend>
  <table width="100%"  class="table_form">
  <tr>
    <th width="80">站点视图：</th>
    <td class="y-bg">
      <select name="info[view]" id="view">
      <option value="xview">系统默认视图</option>
      {if is_array($view_list)}
      {loop $view_list $key $val}
      <option value="{$val}"{if $data['view'] == $val} selected{/if}>{$val}</option>
      {/loop}
      {/if}
      </select>
    </td>
  </tr>
</table>
</fieldset>
<div class="bk10"></div>
<fieldset>
  <legend>SEO配置</legend>
  <table width="100%"  class="table_form">
  <tr>
    <th width="80">站点标题：</th>
    <td class="y-bg"><input type="text" class="input-text required" name="info[site_title]" id="site_title" size="30" value="<?php echo $data['site_title']?>" /></td>
  </tr>
  <tr>
    <th>关键词：</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[keywords]" id="keywords" size="30" value="<?php echo $data['keywords']?>" /></td>
  </tr>
    <tr>
    <th>描述：</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[description]" id="description" size="30" value="<?php echo $data['description']?>" /></td>
  </tr>
</table>
</fieldset>
<div class="bk10"></div>
<fieldset>
  <legend>附件配置</legend>
  <table width="100%"  class="table_form">
  <tr>
    <th width="130" valign="top">允许上传附件大小</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[setting][upload_maxsize]" id="upload_maxsize" size="10" value="<?php echo $setting['upload_maxsize'] ? $setting['upload_maxsize'] : '2000' ?>"/> KB </td>
  </tr>
  <tr>
    <th width="130" valign="top">允许上传附件类型</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[setting][upload_allowext]" id="upload_allowext" size="50" value="<?php echo $setting['upload_allowext']?>"/></td>
  </tr>  
    <tr>
    <th>是否支持GD库</th>
    <td class="y-bg"><?php echo $this->check_gd()?></td>
</tr>
  <tr>
    <th>是否开启图片水印</th>
    <td class="y-bg">
    <label><input class="radio_style" name="info[setting][watermark_enable]" value="1" <?php echo $setting['watermark_enable']==1 ? 'checked="checked"' : ''?> type="radio"> 启用</label>
    <label><input class="radio_style" name="info[setting][watermark_enable]" value="0" <?php echo $setting['watermark_enable']==0 ? 'checked="checked"' : ''?> type="radio"> 关闭</label>
     </td>
  </tr>    
  <tr>
    <th>水印添加条件</th>
    <td class="y-bg"><label><span class="info">宽</span>
<input type="text" class="input-text" name="info[setting][watermark_minwidth]" id="watermark_minwidth" size="10" value="<?php echo $setting['watermark_minwidth'] ? $setting['watermark_minwidth'] : '300' ?>" /><span class="info">px</span></label> <label><span class="info">高</span><input type="text" class="input-text" name="info[setting][watermark_minheight]" id="watermark_minheight" size="10" value="<?php echo $setting['watermark_minheight'] ? $setting['watermark_minheight'] : '300' ?>" /><span class="info">px</span></label>
     </td>
  </tr>
  <tr>
    <th width="130" valign="top">水印图片</th>
    <td class="y-bg"><input type="text" name="info[setting][watermark_img]" id="watermark_img" size="30" value="<?php echo $setting['watermark_img'] ? $setting['watermark_img'] : 'mark.gif' ?>"/><span class="info">水印存放路径:站点域名/images/water</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">水印透明度</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[setting][watermark_pct]" id="watermark_pct" size="10" value="<?php echo $setting['watermark_pct'] ? $setting['watermark_pct'] : '100' ?>" /><span class="info">请设置为0-100之间的数字，0代表完全透明，100代表不透明</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">JPEG 水印质量</th>
    <td class="y-bg"><input type="text" class="input-text" name="info[setting][watermark_quality]" id="watermark_quality" size="10" value="<?php echo $setting['watermark_quality'] ? $setting['watermark_quality'] : '80' ?>" /><span class="info">水印质量请设置为0-100之间的数字,决定 jpg 格式图片的质量</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">水印位置</th>
    <td>
    <table width="100%" class="radio-label">
  <tr>
  <td rowspan="3"><label><input class="radio_style" name="info[setting][watermark_pos]" value="10" type="radio" <?php echo ($setting['watermark_pos']==10) ? 'checked':''?>> 随机位置</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="1" type="radio" <?php echo ($setting['watermark_pos']==1) ? 'checked':''?>> 顶部居左</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="2" type="radio" <?php echo ($setting['watermark_pos']==2) ? 'checked':'' ?>> 顶部居中</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="3" type="radio" <?php echo ($setting['watermark_pos']==3) ? 'checked':''?>>  顶部居右</label></td>
  </tr>
  <tr>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="4" type="radio" <?php echo ($setting['watermark_pos']==4) ? 'checked':''?>> 中部居中</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="5" type="radio" <?php echo ($setting['watermark_pos']==5) ? 'checked':''?>> 中部居左</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="6" type="radio" <?php echo ($setting['watermark_pos']==6) ? 'checked':''?>> 中部居右</label></td>
    </tr>
  <tr>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="7" type="radio" <?php echo ($setting['watermark_pos']==7) ? 'checked':''?>> 底部居左</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="8" type="radio" <?php echo ($setting['watermark_pos']==8) ? 'checked':''?>> 底部居中</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="9" type="radio" <?php echo ($setting['watermark_pos']==9) ? 'checked':''?>> 底部居右</label></td>
    </tr>
</table>
      </td></tr>
</table>
</fieldset>
</div>
<div class="formBar">
  <ul>
    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
    </form>
</div>