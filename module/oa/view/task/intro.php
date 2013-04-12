<div class="pageContent">
<div class="pageFormContent" layoutH="52">
	{if $info['attachment']}
	{php $attachment = string2array($info['attachment'])}
	<fieldset>
		<legend>附件</legend>
		{loop $attachment $r}
		<li><a href="{$siteinfo['url']}{substr($r,1)}" target="_blank">{$siteinfo['url']}{substr($r,1)}</a></li>
		{/loop}
	</fieldset>
	{/if}
	{if $info['intro']}
	<fieldset>
		<legend>备注</legend>
		{$info['intro']}
	</fieldset>
	{/if}
</div>
<div class="formBar">
  <ul>
    <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
  </ul>
</div>
</div>