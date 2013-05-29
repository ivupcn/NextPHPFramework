<?php
return array(
	'database' => array(
		'default' => array(
			'dsn' => 'mysql:host=localhost;port=3306;dbname=x',
			'username'    => 'root',
			'password'    => '',
			'tablepre'    => 'x_',
			'attribute' => array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'),
			'debug'       => true,
			'pconnect'    => 0,
			'autoconnect' => 0
		),
		'sqlsrv' => array(
			'dsn' => 'sqlsrv:server=localhost;Database=vod',
			'username'    => 'sa',
			'password'    => '42920617',
			'tablepre'    => 'x_',
			'attribute' => array(),
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