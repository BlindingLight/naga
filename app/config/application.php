<?php

return array(
	'timezone' => 'Europe/Berlin',

	'defaultRoute' => 'home',
	'defaultRouteIfLoggedIn' => 'home',

	'resourceRoot' => '/assets/',

	'templates' => (object)array(
		'root' => '../app/template',
		'compiled' => '../app/storage/template/compiled',
		'cached' => '../app/storage/template/cached',
	)
);