<?php

$app->profiler()->createTimer('Initializing DatabaseManager and adding database connections and QueryBuilder');
$app->database = new Naga\Core\Database\DatabaseManager();
if ($app->config()->exists('databases'))
{
	$app->database(null)->addConnections(
		$app->database(null)->getConnectionsFromConfigArray(
			$app->config('databases')->toArray()
		)
	);

	// TODO: create query builder instance for each database connection
	$db = $app->config('databases')->toArray();
	$db = $db['default'];
	$app->queryBuilder = new \Naga\Core\Database\MySqlQueryBuilder(
		'default',
		$db->host,
		$db->port,
		$db->user,
		$db->password,
		$db->database,
		$db->persistent,
		$db->lazyConnect
	);
}
$app->profiler()->stopTimer('Initializing DatabaseManager and adding database connections and QueryBuilder');