<?php

return array(
	'timezone' => 'Europe/Berlin',

	'defaultRoute' => 'not-found',
	'defaultRouteIfLoggedIn' => 'not-found',

	'resourceRoot' => '/assets/',

	'templates' => (object)array(
		'root' => '../app/template',
		'compiled' => '../app/storage/template/compiled',
		'cached' => '../app/storage/template/cached',
	)
);