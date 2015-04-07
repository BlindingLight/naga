<?php

if ($app->config('application::debug'))
{
	// enable profiling
	\Naga\Core\Debug\Profiler::enableGlobally();

	// display errors and error reporting
	ini_set('display_errors', 1);
	error_reporting(
		$app->config('application::errorReportingLevel') !== null
		? $app->config('application::errorReportingLevel')
		: E_ALL | E_STRICT
	);

	// whoops init
	if ($app->config('application::whoopsEnabled'))
	{
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
		$whoops->register();
	}
}