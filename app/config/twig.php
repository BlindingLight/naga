<?php

use Naga\Core\Application;

return [
	// debug mode
	'debug' => true,

	// template config
	'templates' => (object)[
		'root' => '../app/template',
		'compiled' => '../app/storage/template/compiled',
		'cached' => '../app/storage/template/cached',
	],

	// twig filters to register
	// callable: first argument is always an Application instance
	// string: you can use this to register php functions like floor or ceil
	'filters' => [
		// localization filters
		'localize' => function(Application &$app, $constant) {
			return $app->localization()->get($constant);
		},
		'l' => function(Application &$app, $constant) {
			return $app->localization()->get($constant);
		},
		// url generator filter
		'url' => function(Application &$app, $route, $properties = '') {
			return $app->urlGenerator()->route($route, $properties, false, false);
		},
		// resource url generator filter
		'resource' => function(Application &$app, $route, $properties = '') {
			return $app->urlGenerator()->resource($route, $properties, false, false);
		},
		// group thousands filter
		'groupThousands' => function(Application &$app, $val, $decPoint = '.', $thousandSep = ',') {
			$tmp = explode('.', $val);
			$decimals = count($tmp) > 1 ? strlen($tmp[count($tmp) - 1]) : 0;
			return number_format($val, $decimals, $decPoint, $thousandSep);
		},
		// floor and ceil
		'floor' => 'floor',
		'ceil' => 'ceil',
	]
];