<?php

// setting timezone
$app->profiler()->createTimer('Setting timezone');
if (!date_default_timezone_set($app->config('application')->get('timezone')))
	date_default_timezone_set('UTC');
$app->profiler()->stopTimer('Setting timezone');

// localization init
$app->profiler()->createTimer('Initializing Localization');
$app->localization = new \Naga\Core\Localization\Localization();
$app->profiler()->stopTimer('Initializing Localization');