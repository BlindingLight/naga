<?php

$baseDir = __DIR__ . '/bootstrap/';

// checking if we need to regenerate _generated.php (php bootstrap.php --force)
if ((!isset($argv[1]) || $argv[1] != 'update') && file_exists("{$baseDir}_generated.php"))
	require_once "{$baseDir}_generated.php";

// bootstrap files to load (ordered list)
$loadFiles = array(
	'autoloader',
	'app',
	'events',
	'filesystem',
	'config',
	'debug',
	'validator',
	'session',
	'hasher',
	'auth',
	'request',
	'input',
	'cookie',
	'router',
	'urlgenerator',
	'cache',
	'databases',
	'email',
	'localization',
	'proxyclasses',
	'custom'
);

// loading files
$content = '';
foreach ($loadFiles as $fileName)
{
	$filePath = "{$baseDir}{$fileName}.php";
	if (file_exists($filePath))
		$content .= str_replace('<?php', '', file_get_contents($filePath));
}

$content .= "\n\$app->profiler()->stopTimer('Bootstrap time');";
file_put_contents("{$baseDir}_generated.php", "<?php {$content}");