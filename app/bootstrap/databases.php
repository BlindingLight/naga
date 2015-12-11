<?php

$app->profiler()->createTimer('Initializing DatabaseManager and adding database connections');
$app->databaseManager = new Naga\Core\Database\DatabaseManager();
if ($app->config()->exists('databases'))
{
	$app->databaseManager()->addConnections(
		$app->databaseManager()->getConnectionsFromConfigArray(
			$app->config('databases')->toArray()
		)
	);
}
$app->profiler()->stopTimer('Initializing DatabaseManager and adding database connections');