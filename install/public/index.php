<?php

ini_set('display_errors', 1);
error_reporting(E_ALL | E_NOTICE);
try
{
	require_once('../app/bootstrap.php');

	$app->run();
	$app->finish();
}
catch (Exception $e)
{
	echo $e->getMessage();
}