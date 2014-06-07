<?php

ini_set('display_errors', 1);
error_reporting(E_ALL | E_NOTICE);
try
{
	require_once('../app/bootstrap.php');

	$app->init();
	$app->run();
	$app->finish();

	if (!$app->input()->isJson())
	{
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