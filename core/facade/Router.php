<?php

namespace Naga\Core\Facade;

use Naga\Core\Exception;

class Router extends Facade
{
	/**
	 * @var string component name
	 */
	protected static $_accessor = 'router';

	/**
	 * Sets the default route. This will be used if no match found.
	 *
	 * @param string $routeName
	 * @throws Exception\Routing\RouteNotFoundException
	 */
	public static function setDefaultRoute($routeName)
	{
		return static::component()->setDefaultRoute($routeName);
	}

	/**
	 * Gets the default route name.
	 *
	 * @return string
	 */
	public static function defaultRouteName()
	{
		return static::component()->defaultRouteName();
	}

	/**
	 * Routes the request uri. And returns the executed route function/method result.
	 *
	 * @return \Naga\Core\Action\Action|mixed
	 * @throws Exception\Routing\RouteBadlyConfiguredException
	 */
	public static function routeUri()
	{
		return static::component()->routeUri();
	}

	/**
	 * Adds a route. Route must be a callable function or a string with format 'className[at]methodName'.
	 *
	 * @param string $mappedUrl
	 * @param \Callable|string $route
	 * @return Router
	 * @throws Exception\Routing\RouteAlreadyExistsException
	 */
	public static function addRoute($mappedUrl, $route)
	{
		return static::component()->addRoute($mappedUrl, $route);
	}

	/**
	 * Adds multiple routes.
	 *
	 * @param array $routes
	 */
	public static function addRoutes(array $routes)
	{
		return static::component()->addRoutes($routes);
	}
}