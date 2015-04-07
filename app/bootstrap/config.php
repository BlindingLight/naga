<?php

// config init
$app->profiler()->createTimer('Initializing Config');
$app->config = new \Naga\Core\Config\Config($app->fileSystem());
$app->config()->getFilesInDirectory(__DIR__ . '/../config', 'json');
$app->config()->getFilesInDirectory(__DIR__ . '/../config', 'php');

// adding external classes to autoloader
$app->profiler()->createTimer('Configuring external classes');
if ($app->config('externalclasses::classes'))
{
	$autoloader->addExternalClasses(
		$app->config('externalclasses::classes')
	);
}
if ($app->config('externalclasses::resolvers'))
{
	$autoloader->addExternalResolvers(
		$app->config('externalclasses::resolvers')
	);
}
$app->profiler()->stopTimer('Configuring external classes');

$app->profiler()->stopTimer('Initializing Config');