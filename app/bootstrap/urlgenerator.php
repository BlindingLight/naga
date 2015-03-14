<?php

// url generator init
$app->profiler()->createTimer('Initializing UrlGenerator');
$app->urlgenerator = new \Naga\Core\Routing\UrlGenerator(
	$app->config('routes')->toArray(),
	$app->request(),
	$app->config('application')->get('resourceRoot')
);
$app->profiler()->stopTimer('Initializing UrlGenerator');