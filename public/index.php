<?php

declare(strict_types=1);

// running bootstrap code
// if _generated.php doesn't exist, please run php app/bootstrap.php
require_once __DIR__ . '/../app/bootstrap/_generated.php';

// App initialization tasks
$app->init();

function lolz(): object
{
	return (object)[];
}

dump(lolz());
// App tasks based on current request
$app->run();

// App finish tasks
$app->finish();

// sending debug information
if ($app->config('application::debug') && !$app->input()->isJson())
{
	// sending log
	$app->logger()->dispatch();
	// sending profiler data
	$app->profiler()->dispatchLog();
	// sending profiler data for each registered component
	foreach ($app->registeredComponents() as $component)
		$component->instance->profiler()->dispatchLog();
}