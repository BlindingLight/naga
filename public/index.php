<?php

ini_set('display_errors', 1);
error_reporting(E_ALL | E_NOTICE);
try
{
	require_once('../core/bootstrap.php');

	$app->init();
	$app->run();
	$app->finish();
}
catch (Exception $e)
{
	echo $e->getMessage();
}