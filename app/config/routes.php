<?php

return array(
	'404' => array(
		'as' => 'not-found',
		'get' => function() {
			return 'Content not found.';
		},
	),
	'home' => array(
		'as' => 'home',
		'get' => '\App\Controller\Naga\WelcomeController@getWelcome'
	),
);