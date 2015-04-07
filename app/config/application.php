<?php

return [
	// site's timezone
	'timezone' => 'Europe/Berlin',

	// debug mode
	'debug' => true,

	// error reporting level if debug is true
	'errorReportingLevel' => E_ALL | E_STRICT,

	// enable Whoops as error handler
	'whoopsEnabled' => true,

	// start session automatically
	'autoStartSession' => false,

	// session cookie name
	'sessionCookieName' => 'PHPSESSID',

	// CSRF token input name
	'csrfTokenInputName' => '_csrfToken',

	// default route if user is not logged in
	'defaultRoute' => 'home',
	// default route if user is logged in
	'defaultRouteIfLoggedIn' => 'home',

	// resource root used by urlGenerator()->resource()
	'resourceRoot' => '/assets/',
];