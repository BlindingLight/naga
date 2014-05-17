<?php

return array(
	'resolvers' => array(
		'Twig' => function($className)
		{
			return str_replace(array('_', "\0"), array('/', ''), $className) . '.php';
		},
		'Swift' => function($className)
		{
			$dirs = explode('_', $className);
			$classPath = 'SwiftMailer/classes/';
			$className = array_pop($dirs);
			foreach ($dirs as $dir)
				$classPath .= $dir . '/';

			return $classPath . $className . '.php';
		}
	),
	'classes' => array(

	)
);