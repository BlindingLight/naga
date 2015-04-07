<?php

// session init
$app->profiler()->createTimer('Initializing SessionManager');
$app->session = new \Naga\Core\Session\SessionManager(new \Naga\Core\Session\Storage\Native());
if ($app->config('application::autoStartSession') || $app->cookie()->exists($app->config('application::sessionCookieName')))
	$app->session()->start($app->cookie()->get($app->config('application::sessionCookieName')));

$app->profiler()->stopTimer('Initializing SessionManager');