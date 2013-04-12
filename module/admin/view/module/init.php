<div class="pageContent">
    <table class="table" width="100%">
        <thead>
            <tr>
            <th align="center">模块名称</th>
			<th align="center">模块目录</th>
			<th align="center">版本号</th>
			<th align="center">安装日期</th>
			<th align="center">更新日期</th>
			<th align="center">管理操作</th>
            </tr>
        </thead>
	    <tbody>
	 	<?php 
		if (is_array($directory)){
			foreach ($directory as $d){
				if (array_key_exists($d, $modules)) {
		?>  
			<tr>
			<td align="center"><?php echo $modules[$d]['name']?></td>
			<td align="center"><?php echo $d?></td>
			<td align="center"><?php echo $modules[$d]['version']?></td>
			<td align="center"><?php echo $modules[$d]['installdate']?></td>
			<td align="center"><?php echo $modules[$d]['updatedate']?></td>
			<td align="center"> 
			<?php if ($modules[$d]['iscore']) {?><font color="#999">禁止</font><?php } else {?><a href="{url 'module::uninstall@admin','module/'.$d}" target="dialog" mask="true" maxable="false" rel="admin_module_uninstall" width="500" height="250"><font color="red">卸载</font></a><?php }?>
			</td>
			</tr>
		<?php 
			} else {  
				$moduel = $isinstall = $modulename = '';
				if (file_exists(Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$d.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'config.php')) {
					require Next::config('system','module_path',APP_PATH.'module'.DIRECTORY_SEPARATOR).$d.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'config.php';
					$isinstall = '安装';
				} else {
					$module = '未知';
					$isinstall = '无法安装';
				}
		?>
			<tr class="on">
			<td align="center"><?php echo $modulename?></td>
			<td align="center"><?php echo $d?></td>
			<td align="center">未知</td>
			<td align="center">未知</td>
			<td align="center">未安装</td>
			<td align="center">
			<?php if ($isinstall!='无法安装') {?> <a href="{url 'module::install@admin','module/'.$d}" target="dialog" mask="true" maxable="false" rel="admin_module_install" width="500" height="250"><font color="#009933"><?php echo $isinstall?></font><?php } else {?><font color="#009933"><?php echo $isinstall?></font><?php }?></a>
			</td>
			</tr>
		<?php 
				}
			}
		}
		?>
	</tbody>
    </table>
</div>