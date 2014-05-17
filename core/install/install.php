<?php

/**
 * @file This file is used to create base wireframe for Naga apps.
 */

error_reporting(0);
set_time_limit(0);
$projectRoot = '../../';
$appRoot = "{$projectRoot}app";
$externalRoot = "{$projectRoot}external";
$publicRoot = "{$projectRoot}public";

$actionRoot = "{$appRoot}/action";
$configRoot = "{$appRoot}/config";
$controllerRoot = "{$appRoot}/controller";
$storageRoot = "{$appRoot}/storage";
$storageTemplateRoot = "{$storageRoot}/template";
$storageTemplateCompiledRoot = "{$storageRoot}/template/compiled";
$storageTemplateCachedRoot = "{$storageRoot}/template/cached";
$templateRoot = "{$appRoot}/template";
$utilRoot = "{$appRoot}/util";
$modelRoot = "{$appRoot}/model";
$viewRoot = "{$appRoot}/view";

try
{
	echo "###########################################################\n";
	echo "# Naga install script v0.1\n";
	echo "###########################################################\n\n";
	echo 'Project root is ' . realpath($projectRoot) . "\n";

	createDirectory('app', $appRoot);
	createDirectory('external', $externalRoot);

	$publicTmp = getConsoleInput("Public directory (press enter for {$publicRoot}): ");
	if ($publicTmp)
		$publicRoot = $publicTmp;

	createDirectory('public', $publicRoot);

	echo "Creating app directories\n";
	createDirectory('app/action', $actionRoot);
	createDirectory('app/config', $configRoot);
	createDirectory('app/controller', $controllerRoot);
	createDirectory('app/storage', $storageRoot);
	createDirectory('app/storage/template', $storageTemplateRoot);
	createDirectory('app/storage/template/compiled', $storageTemplateCompiledRoot);
	createDirectory('app/storage/template/cached', $storageTemplateCachedRoot);
	createDirectory('app/template', $templateRoot);
	createDirectory('app/util', $utilRoot);
	createDirectory('app/model', $modelRoot);
	createDirectory('app/view', $viewRoot);

	copyDirectoryContent('app', $appRoot);
	copyDirectoryContent('external', $externalRoot);
	copyDirectoryContent('public', $publicRoot);
}
catch (\Exception $e)
{
	echo $e->getMessage() . "\n";
}

function copyDirectoryContent($from, $to)
{
	$files = scandir($from);
	array_shift($files);
	array_shift($files);

	foreach ($files as $file)
	{
		$filePath = realpath($from . '/' . $file);
		$file = basename($file);
		if (is_dir($filePath))
		{
			createDirectory($file, $to . '/' . $file);
			copyDirectoryContent($filePath, $to . '/' . $file);
		}
		else
		{
			echoDotted("Copying {$file}");
			if (copy($filePath, "{$to}/{$file}"))
			{
				chmod("{$to}/{$file}", 0777);
				echo "ok\n";
			}
			else
				echo "failed\n";
		}
	}
}

function createDirectory($name, $path)
{
	echoDotted('Creating ' . $name . ' directory (' . $path . ')');
	if (file_exists($path) && is_dir($path))
		echo "already exists\n";
	else
	{
		if (mkdir($path))
		{
			echo "ok\n";
		}
		else
		{
			echo "failed\n";
			throw new \Exception("Could not create {$name} directory, installation failed.");
		}
	}

	chmod($path, 0777);
}

function getConsoleInput($message)
{
	echo $message;
	$handle = fopen ('php://stdin', 'r');

	return trim(fgets($handle));
}

function echoDotted($text, $characters = 100)
{
	$len = $characters - strlen($text);
	if ($len < 0)
		$len = 0;

	echo $text . str_repeat('.', $len);
}