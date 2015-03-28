<?php

$app->profiler()->createTimer('Initializing DatabaseManager and adding database connections');
$app->database = new Naga\Core\Database\DatabaseManager();
if ($app->config()->exists('databases'))
{
	$app->database(null)->addConnections(
		$app->database(null)->getConnectionsFromConfigArray(
			$app->config('databases')->toArray()
		)
	);
}
$app->profiler()->stopTimer('Initializing DatabaseManager and adding database connections');