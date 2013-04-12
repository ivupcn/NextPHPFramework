<?php defined('IN_Next') or exit('No permission resources.'); ?><style type="text/css">
.radio-label{ border-top:1px solid #e4e2e2; border-left:1px solid #e4e2e2;}
.radio-label td{ border-right:1px solid #e4e2e2; border-bottom:1px solid #e4e2e2;}
</style>
<div class="pageContent">
<form action="?m=admin&c=site&a=add" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
  <div class="pageFormContent" layoutH="52">
<fieldset>
	<legend>基本配置</legend>
	<table width="100%"  class="table_form">
  <tr>
    <th width="80">站点名称：</th>
    <td><input type="text" class="input-text required" name="info[name]" id="name" size="30" /></td>
  </tr>
  <tr>
    <th>站点目录：</th>
    <td><input type="text" class="input-text required lettersonly" name="info[dirname]" id="dirname" size="30" /></td>
  </tr>
    <tr>
    <th>站点域名：</th>
    <td><input type="text" class="input-text required" name="info[domain]" id="domain"  size="30"/></td>
  </tr>
  <tr>
    <th>站点LOGO：</th>
    <td><input type="text" class="input-text required" name="info[logo]" id="logo" size="30" /></td>
  </tr>
</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
	<legend>SEO配置</legend>
	<table width="100%"  class="table_form">
  <tr>
    <th width="80">站点标题：</th>
    <td><input type="text" class="input-text required" name="info[site_title]" id="site_title" size="30" /></td>
  </tr>
  <tr>
    <th>关键词：</th>
    <td><input type="text" class="input-text" name="info[keywords]" id="keywords" size="30" /></td>
  </tr>
    <tr>
    <th>描述：</th>
    <td><input type="text" class="input-text" name="info[description]" id="description" size="30" /></td>
  </tr>
</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
	<legend>附件配置</legend>
	<table width="100%"  class="table_form">
  <tr>
    <th width="130" valign="top">允许上传附件大小：</th>
    <td><input type="text" class="input-text" name="info[setting][upload_maxsize]" id="upload_maxsize" size="10" value="2000"/> <span class="info">KB</span> </td>
  </tr>
  <tr>
    <th width="130" valign="top">允许上传附件类型：</th>
    <td><input type="text" class="input-text" name="info[setting][upload_allowext]" id="upload_allowext" size="50" value="jpg|jpeg|gif|bmp|png|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|rar|zip|swf"/></td>
  </tr>    
    <tr>
    <th>是否支持GD库：</th>
    <td><?php echo $this->check_gd()?></td>
	</tr>
  <tr>
    <th>是否开启图片水印：</th>
    <td>
	  <label><input class="radio_style" name="info[setting][watermark_enable]" value="1" type="radio"> 启用</label>&nbsp;&nbsp;&nbsp;&nbsp;
	  <label><input class="radio_style" name="info[setting][watermark_enable]" value="0" checked="checked" type="radio">关闭</label>
     </td>
  </tr>    
  <tr>
    <th>水印添加条件：</th>
    <td><label><span class="info">宽</span>
<input type="text" class="input-text" name="info[setting][watermark_minwidth]" id="watermark_minwidth" size="10" value="300" /><span class="info">px</span></label> <label><span class="info">高</span><input type="text" class="input-text" name="info[setting][watermark_minheight]" id="watermark_minheight" size="10" value="300" /><span class="info">px</span></label>
     </td>
  </tr>
  <tr>
    <th width="130" valign="top">水印图片：</th>
    <td><input type="text" class="input-text"  name="info[setting][watermark_img]" id="watermark_img" size="30" value="mark.gif" /><span class="info">水印存放路径:站点域名/images/water</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">水印透明度：</th>
    <td><input type="text" class="input-text" name="info[setting][watermark_pct]" id="watermark_pct" size="10" value="100" /><span class="info">请设置为0-100之间的数字，0代表完全透明，100代表不透明</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">JPEG 水印质量：</th>
    <td><input type="text" class="input-text" name="info[setting][watermark_quality]" id="watermark_quality" size="10" value="100" /><span class="info">水印质量请设置为0-100之间的数字,决定 jpg 格式图片的质量</span></td>
  </tr> 
   <tr>
    <th width="130" valign="top">水印位置：</th>
    <td>
    <table width="100%" class="radio-label">
  <tr>
  <td rowspan="3"><label><input class="radio_style" name="info[setting][watermark_pos]" value="10" type="radio" > 随机位置</label></td>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="1" type="radio" > 顶部居左</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="2" type="radio"> 顶部居中</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="3" type="radio"> 顶部居右</label></td>
  </tr>
  <tr>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="4" type="radio"> 中部居中</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="5" type="radio"> 中部居左</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="6" type="radio" > 中部居右</label></td>
    </tr>
  <tr>
    <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="7" type="radio"> 底部居左</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="8" type="radio"> 底部居中</label></td>
	  <td><label><input class="radio_style" name="info[setting][watermark_pos]" value="9" type="radio" checked> 底部居右</label></td>
    </tr>
</table>
</td></tr>
</table>
</fieldset>
<div class="bk15"></div>
<fieldset>
  <legend>默认路由配置</legend>
  <table width="100%"  class="table_form">
  <tr>
    <th>M：</th>
    <td><input type="text" class="input-text" name="info[route][m]" id="m" size="20" value="index" /></td>
    <th>C：</th>
    <td><input type="text" class="input-text" name="info[route][c]" id="c" size="20" value="index" /></td>
    <th>A：</th>
    <td><input type="text" class="input-text" name="info[route][a]" id="a" size="20" value="init" /></td>
  </tr>
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