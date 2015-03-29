<?php

return [
	'404' => [
		'as' => 'not-found',
		'get' => function() {
			return 'Content not found.';
		},
	],
	'home' => [
		'as' => 'home',
		'get' => '\App\Controller\Naga\WelcomeController@getWelcome'
	],
];