<?php

// session init
$app->profiler()->createTimer('Initializing SessionManager');
$app->session = new \Naga\Core\Session\SessionManager(new \Naga\Core\Session\Storage\Native());
if ($app->config('application')->get('autoStartSession'))
	$app->session()->start();

$app->profiler()->stopTimer('Initializing SessionManager');