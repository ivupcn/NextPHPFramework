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
			)
		),
	'acl' => array(
		'acl_localhost' => array('role' => '1'),
		'admin' => array('index' => array('login' => array('role' => 'ACL_NO_ROLE'))),
		'user' => array('index' => array('login' => array('role' => 'ACL_NO_ROLE'))),
		'test' => array('test' => array('init' => array('role' => 'ACL_EVERYONE'))),
	)
);
?>