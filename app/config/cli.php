<?php

use \Naga\Core\Debug\Log\iLogger;

return array(
	// minimum severity to log
	'minSeverity' => iLogger::Debug,
	// allow severity prefixes
	'severityPrefixesEnabled' => true,
	// severity prefixes prepended to messages
	'severityPrefixes' => array(
		iLogger::Emergency => '[EMERGENCY] ',
		iLogger::Alert => '[ALERT] ',
		iLogger::Critical => '[CRITICAL] ',
		iLogger::Error => '[ERROR] ',
		iLogger::Warning => '[WARNING] ',
		iLogger::Notice => '[NOTICE] ',
		iLogger::Info => '[INFO] ',
		iLogger::Debug => '[DEBUG] '
	),
	// enable colorized output
	'colorsEnabled' => true,
	// use [colorName](text) to colorize output
	'severityPrefixColors' => array(
		iLogger::Emergency => iLogger::Red,
		iLogger::Alert => iLogger::Red,
		iLogger::Critical => iLogger::LightRed,
		iLogger::Error => iLogger::LightRed,
		iLogger::Warning => iLogger::Yellow,
		iLogger::Notice => iLogger::Cyan,
		iLogger::Info => iLogger::White,
		iLogger::Debug => iLogger::Cyan
	)
);