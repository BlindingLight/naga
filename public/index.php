<?php

try
{
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
}
catch (Exception $e)
{
	if (!$app->input()->isJson())
	{
		$app->setLogger(new \Naga\Core\Debug\Log\JsConsoleLogger('Unhandled exception'));
		$app->logger()->error("%s\n\t->\n\t\t%s", $e->getMessage(), $e->getTraceAsString())->dispatch();
	}
}