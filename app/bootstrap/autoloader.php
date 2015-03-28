<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$autoloader = new \Naga\Core\Autoloader();
$autoloader->setRootDirectory(__DIR__ . '/../../');

spl_autoload_register(
	function($className) use($autoloader)
	{
		$autoloader->autoload($className);
	},
	true,
	false
);