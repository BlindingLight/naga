<?php

chdir(realpath(__DIR__));

require_once('core/iComponent.php');
require_once('core/nComponent.php');
require_once('core/Autoloader.php');

$autoloader = new \Naga\Core\Autoloader();
$autoloader->setRootDirectory(__DIR__);

spl_autoload_register(
	function($className) use($autoloader)
	{
		$autoloader->autoload($className);
	},
	true,
	true
);

// config init
$config = new \Naga\Core\Config\Config(new \Naga\Core\FileSystem\FileSystem());
$config->getFilesInDirectory('app/config', 'json');
$config->getFilesInDirectory('app/config', 'php');

$cli = new \Naga\Core\Cli\CommandLine($config->getConfigBag('cli'));