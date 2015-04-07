<?php

return [
	'resolvers' => [
		'Twig' => function($className)
		{
			return 'twig/twig/lib/' . str_replace(['_', "\0"], ['/', ''], $className) . '.php';
		},
		'Swift' => function($className)
		{
			$dirs = explode('_', $className);
			$classPath = 'swiftmailer/swiftmailer/lib/classes/';
			$className = array_pop($dirs);
			foreach ($dirs as $dir)
				$classPath .= $dir . '/';

			return $classPath . $className . '.php';
		}
	],
	'classes' => [

	]
];