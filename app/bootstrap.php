<?php

/**
 * @file Concatenates bootstrap code into app/bootstrap/{checksum}.php. Run this file
 * 	every time you make changes in any of the files in app/bootstrap directory.
 */

$baseDir = __DIR__ . '/bootstrap/';
$generatedPath = "{$baseDir}_generated.php";

// bootstrap files to load (please don't change the order)
$bootstrapFiles = [
	'autoloader',
	'app',
	'events',
	'filesystem',
	'config',
	'debug',
	'validator',
	'cookie',
	'session',
	'hasher',
	'auth',
	'request',
	'input',
	'router',
	'urlgenerator',
	'cache',
	'databases',
	'email',
	'localization',
	'proxyclasses',
	'custom'
];

// loading files
$content = '';
foreach ($bootstrapFiles as $fileName)
{
	$filePath = "{$baseDir}{$fileName}.php";
	if (file_exists($filePath))
		$content .= str_replace(['<?php', '?>'], '', file_get_contents($filePath));
}

$content .= "\n\$app->profiler()->stopTimer('Bootstrap time');";
file_put_contents($generatedPath, "<?php {$content}");

if (isset($argv[1]) && ($argv[1] == 'true' || $argv[1] == 'minify'))
	file_put_contents($generatedPath, php_strip_whitespace($generatedPath));

require_once $generatedPath;