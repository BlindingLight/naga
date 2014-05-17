<?php

// if auto-creating connection instances from this config,
// the connection name (array key) will always be passed as first argument
return array(
	'default' => (object)array(
		'type' => '\Naga\Core\Database\Connection\PostgreSql\PgSqlConnection',
		'host' => '127.0.0.1',
		'port' => 5432,
		'user' => '',
		'password' => '',
		'database' => '',
		'persistent' => false,
		'lazyConnect' => true
	)
);