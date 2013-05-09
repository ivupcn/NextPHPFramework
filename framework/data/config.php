<?php
return array(
	'database' => array(
		'default' => array(
			'driver' => 'mysql',
			'hostname'    => 'localhost',
			'dbport'      => '3306',
			'database'    => 'x',
			'username'    => 'root',
			'password'    => '',
			'tablepre'    => 'x_',
			'charset'     => 'utf8',
			'debug'       => true,
			'pconnect'    => 0,
			'autoconnect' => 0
		)
	),
	'acl' => array(
		'acl_localhost' => array('role' => '1')
	)
);
?>