<?php

if ($app->config('application')->get('debug'))
{
	// enable profiling
	\Naga\Core\Debug\Profiler::enableGlobally();

	// display errors and error reporting
	ini_set('display_errors', 1);
	error_reporting(
		$app->config('application')->get('errorReportingLevel')
		? $app->config('application')->get('errorReportingLevel')
		: E_ALL| E_STRICT
	);
}