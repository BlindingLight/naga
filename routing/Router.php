<?php

namespace Naga\Core\Routing;

use Naga\Core\Request\Request;
use Naga\Core\nComponent;
use Naga\Core\Exception;

/**
 * Routes the request uri.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Routing
 */
class Router extends nComponent
{
	/**
	 * @var array
	 */
	private $_routes = array();
	/**
	 * @var array url -> route mappings
	 */
	private $_urlMappings = array();
	/**
	 * @var string
	 */
	private $_defaultRoute = '/';
	/**
	 * @var string
	 */
	private $_matchedMappedUrl;

	/**
	 * Construct.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->registerComponent('request', $request);
	}

	/**
	 * Sets the default route. This will be used if no match found.
	 *
	 * @param string $routeName
	 * @throws \Naga\Core\Exception\ConfigException
	 */
	public function setDefaultRoute($routeName)
	{
		if (!isset($this->_routes[$routeName]))
			throw new Exception\ConfigException("Can't set default route '{$routeName}', doesn't exist.");

		$this->_defaultRoute = $routeName;
	}

	/**
	 * Gets the default route name.
	 *
	 * @return string
	 */
	public function defaultRouteName()
	{
		return $this->_defaultRoute;
	}

	/**
	 * Routes the request uri. And returns the executed route function/method result.
	 *
	 * @return \Naga\Core\Action\Action|mixed
	 * @throws \Naga\Core\Exception\ConfigException
	 */
	public function routeUri()
	{
		$this->profiler()->createTimer('routeUri');

		$route = $this->matchUri($this->request()->uri());
		$route->parameters = $this->getParameters($this->_matchedMappedUrl, $route->parameters);
		$route->method = $this->request()->httpMethodString();

		if (!isset($route->{$route->method}))
			$route->method = 'get';

		if (!isset($route->{$route->method}))
		{
			$this->profiler()->timer('routeUri')->stop();
			throw new Exception\ConfigException("Badly configured route, missing 'get'.");
		}

		if (is_callable($route->{$route->method}))
		{
			$this->profiler()->timer('routeUri')->stop();
			return call_user_func_array($route->{$route->method}, $route->parameters);
		}
		else
		{
			list($className, $methodName) = explode('@', $route->{$route->method});
			try
			{
				$controller = new $className();
				$this->profiler()->timer('routeUri')->stop();
				return $controller->{$methodName}($route->parameters);
			}
			catch (\Exception $e)
			{
				$this->profiler()->timer('routeUri')->stop();
				return $route->{$route->method} . ":\n" . $e->getMessage();
			}
		}
	}

	/**
	 * Creates an associative parameters array for the given mapped url from the $parameters array.
	 *
	 * @param string $mappedUrl
	 * @param array $parameters an array containing parameter values, must be number indexed
	 * @return array
	 */
	protected function getParameters($mappedUrl, $parameters)
	{
		if (!$mappedUrl || $mappedUrl == '/')
			return array();

		$this->profiler()->createTimer('getParameters');
		$parts = explode('/', $mappedUrl);
		$finalized = array();
		foreach ($parts as $part)
		{
			if (preg_match('/{[a-zA-Z0-9-_]+\|.+}/', $part))
				$finalized[preg_replace('/{([a-zA-Z0-9]+)\|(.+)}/', '$1', $part)] = array_shift($parameters);
		}

		$this->profiler()->timer('getParameters')->stop();
		return $finalized;
	}

	/**
	 * Matches the request uri and returns an object with route data.
	 *
	 * @param string $uri
	 * @return object
	 */
	protected function matchUri($uri)
	{
		$this->profiler()->createTimer('matchUri');
		$uri = $uri != '/' ? trim($uri, '/') : $uri;
		foreach ($this->_urlMappings as $mappedUrl => $routeName)
		{
			$pattern = $this->createRegexFromMappedUrl($mappedUrl);
			if (!$pattern)
				continue;

			$matches = array();
			if (preg_match($pattern, $uri, $matches))
			{
				$routeName = $this->_urlMappings[$mappedUrl];
				if (isset($this->_routes[$routeName]->domain)
					&& !$this->domainCheck($this->request()->domainName(), $this->_routes[$routeName]->domain))
				{
					continue;
				}

				$this->_routes[$routeName]->parameters = array_splice($matches, 1, count($matches) - 1);
				$this->_matchedMappedUrl = $mappedUrl;
				$this->profiler()->timer('matchUri')->stop();
				return $this->_routes[$routeName];
			}
		}

		$route = $this->_routes[$this->defaultRouteName()];
		$route->parameters = array();
		$this->profiler()->timer('matchUri')->stop();
		return $route;
	}

	/**
	 * Checks whether the current request domain is valid for the route.
	 *
	 * @param string $domain current domain
	 * @param string $expected expected domain (route domain)
	 * @return bool
	 */
	protected function domainCheck($domain, $expected)
	{
		$this->profiler()->createTimer('domainCheck');
		$domainParts = explode('.', $domain);
		$expectedParts = explode('.', $expected);

		if (count($domainParts) != count($expectedParts))
		{
			$this->profiler()->timer('domainCheck')->stop();
			return false;
		}

		foreach ($domainParts as $idx => $domainPart)
		{
			$expectedPart = $expectedParts[$idx];
			if ($expectedPart != '*' && $domainPart != $expectedPart)
			{
				$this->profiler()->timer('domainCheck')->stop();
				return false;
			}
		}

		return true;
	}

	/**
	 * Creates a regex pattern from a mapped url. Used for matching url -> mapped url matching.
	 *
	 * @param string $mappedUrl
	 * @return string
	 */
	protected function createRegexFromMappedUrl($mappedUrl)
	{
		if (!$mappedUrl || $mappedUrl == '/')
			return '#^/$#';

		$this->profiler()->createTimer('createRegexFromMappedUrl');
		$regex = '/^';
		$parts = explode('/', $mappedUrl);
		foreach ($parts as $idx => $part)
		{
			$regex .= !$idx ? '' : '\/';
			if (preg_match('/{[a-zA-Z0-9]+\|.+}/', $part))
				$regex .= '(' . preg_replace('/{([a-zA-Z0-9]+)\|(.+)}/', '$2', $part) . ')';
			else
				$regex .= $part;
		}

		$this->profiler()->timer('createRegexFromMappedUrl')->stop();
		return $regex . '$/';
	}

	/**
	 * Adds a route. Route must be a callable function or a string with format 'className[at]methodName'.
	 *
	 * @param string $mappedUrl
	 * @param \Callable|string $route
	 * @throws \Naga\Core\Exception\ConfigException
	 */
	public function addRoute($mappedUrl, $route)
	{
		if (isset($this->_routes[$mappedUrl]))
			throw new Exception\ConfigException("Can't add route '$mappedUrl', already exists.");

		$this->profiler()->createTimer('addRoute');
		$route = (object)$route;
		foreach ($this->request()->httpMethodList() as $method)
		{
			if (isset($route->{$method}) && !is_callable($route->{$method}))
			{
				// replacing dots with backslashes
				$route->{$method} = str_replace('.', '\\', $route->{$method});
				// if first char is not \, we prepend it (we use absolute paths)
				if (strpos($route->{$method}, '\\') !== 0)
					$route->{$method} = '\\' . $route->{$method};
			}
		}

		$routeName = isset($route->as) ? $route->as : $mappedUrl;
		$this->_urlMappings[$mappedUrl] = $routeName;

		if (isset($route->sameAs) && isset($this->_routes[$route->sameAs]))
		{
			$this->_routes[$routeName] = $this->_routes[$route->sameAs];
			$this->profiler()->timer('addRoute')->stop();
			return;
		}

		$this->_routes[$routeName] = $route;
		$this->profiler()->timer('addRoute')->stop();
	}

	/**
	 * Adds multiple routes.
	 *
	 * @param array $routes
	 */
	public function addRoutes(array $routes)
	{
		$this->profiler()->createTimer('addRoutes');
		foreach ($routes as $url => $route)
			$this->addRoute($url, $route);
		$this->profiler()->timer('addRoutes')->stop();
	}

	/**
	 * Gets Request instance.
	 *
	 * @return \Naga\Core\Request\Request
	 */
	protected function request()
	{
		return $this->component('request');
	}
}