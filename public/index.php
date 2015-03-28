<?php

require_once '../app/bootstrap.php';

$app->init();
$app->run();
$app->finish();

if ($app->config('application')->get('debug') && !$app->input()->isJson())
{
	$app->logger()->dispatch();
	$app->profiler()->dispatchLog();
	foreach ($app->registeredComponents() as $component)
		$component->instance->profiler()->dispatchLog();
}