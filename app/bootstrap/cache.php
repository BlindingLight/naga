<?php

$app->profiler()->createTimer('Initializing CacheManager and adding cache connections');
$app->cache = new \Naga\Core\Cache\CacheManager();
if ($app->config()->exists('cacheconnections'))
{
	$app->cache->addConnections(
		$app->cache->getConnectionsFromConfigArray(
			$app->config('cacheconnections')->toArray()
		)
	);
}
$app->profiler()->stopTimer('Initializing CacheManager and adding cache connections');