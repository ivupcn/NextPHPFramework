<?php
return array(
	'database' => array(
		'default' => array(
			'hostname'    => 'localhost',
			'database'    => 'x',
			'username'    => 'root',
			'password'    => '',
			'tablepre'    => 'x_',
			'charset'     => 'utf8',
			'type'        => 'mysql',
			'debug'       => true,
			'pconnect'    => 0,
			'autoconnect' => 0
			),
		'pdo' => array(
			'type' => 'pdo',
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
		),
		'mssql' => array(
			'hostname'    => 'localhost\mssql',
			'database'    => 'playlist',
			'username'    => 'sa',
			'password'    => '42920617',
			'tablepre'    => 'x_',
			'charset'     => 'utf8',
			'type'        => 'mssql',
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