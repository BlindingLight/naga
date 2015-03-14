<?php

return array(
	// site's timezone
	'timezone' => 'Europe/Berlin',

	// debug mode
	'debug' => true,

	// error reporting level if debug is true
	'errorReportingLevel' => E_ALL | E_STRICT,

	// start session automatically
	'autoStartSession' => false,

	// default route if user is not logged in
	'defaultRoute' => 'home',
	// default route if user is logged in
	'defaultRouteIfLoggedIn' => 'home',

	// resource root used by urlGenerator()->resource()
	'resourceRoot' => '/assets/',

	// template config
	'templates' => (object)array(
		'root' => '../app/template',
		'compiled' => '../app/storage/template/compiled',
		'cached' => '../app/storage/template/cached',
	)
);