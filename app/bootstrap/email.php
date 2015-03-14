<?php

// checking if email layer is enabled
if ($app->config()->exists('email') && $app->config('email')->get('enabled'))
{
	// SwiftMailer config
	$app->profiler()->createTimer('Initializing SwiftMailer');
	require_once(__DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_init.php');
	Swift::init(
		function ()
		{
			Swift_Preferences::getInstance()->setCharset('UTF-8');
		}
	);
	$app->profiler()->stopTimer('Initializing SwiftMailer');

	// adding email connections
	$app->profiler()->createTimer('Initializing Email and adding email connections');
	$app->email = new \Naga\Core\Email\Email();

	foreach ($app->config('email')->get('connections') as $connectionName => $props)
	{
		$className = $props->connectionClass;
		$app->profiler()->createTimer("Adding connection {$connectionName}.");
		$conn = new $className((object)$props);
		$app->profiler()->stopTimer("Adding connection {$connectionName}.");
		$app->email()->addConnection($connectionName, $conn);
	}

	$app->profiler()->stopTimer('Initializing Email and adding email connections');
}