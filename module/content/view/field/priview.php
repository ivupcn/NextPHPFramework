<div class="pageContent">
	<div class="pageFormContent" layoutH="23">
		<table width="100%" class="table_form contentWrap">
			<tbody>
			{loop $forminfos $field $info}	
			<tr>
		      <th width="80">{if $info['star']}<font color="red">*</font>{/if} {$info['name']}
			  </th>
		      <td>{$info['form']}  {$info['tips']}</td>
		    </tr>
    		{/loop}
    		</tbody>
    	</table>
	</div>
</div>