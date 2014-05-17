<?php

namespace Naga\Core\View\Template;

use Naga\Core\Application;
use Naga\Core\Config\ConfigBag;
use Naga\Core\nComponent;

/**
 * Template implementation for generating content with Twig.
 *
 * @package Naga\Core\View\Template
 * @author  BlindingLight<bloodredshade@gmail.com>
 */
class TwigTemplate extends nComponent implements iTemplate
{
	/**
	 * @var array assigned variables
	 */
	private $_data = array();

	/**
	 * @var \Twig_Environment Twig instance
	 */
	private $_twig;

	/**
	 * @var string template path
	 */
	private $_templatePath = '';

	/**
	 * @var string template root directory
	 */
	private $_templateRoot = '';

	public function __construct(ConfigBag $config, Application $app)
	{
		$this->_templateRoot = $config->get('templates')->root . '/';
		$loader = new \Twig_Loader_Filesystem($this->_templateRoot);
		$this->_twig = new \Twig_Environment(
			$loader,
			array(
				'cache' => $config->get('templates')->compiled,
				'auto_reload' => true
			)
		);

		// registering localize filter
		$localization = $app->localization();
		$this->_twig->addFilter(
			new \Twig_SimpleFilter(
				'localize',
				function($constant) use(&$localization)
				{
					return $localization->get($constant);
				}
			)
		);

		// registering url generator filter
		$urlGenerator = $app->urlGenerator();
		$this->_twig->addFilter(
			new \Twig_SimpleFilter(
				'url',
				function($route, $properties = '') use(&$urlGenerator)
				{
					return $urlGenerator->route($route, $properties, false, false);
				}
			)
		);

		// registering resource url generator filter
		$this->_twig->addFilter(
			new \Twig_SimpleFilter(
				'resource',
				function($path) use(&$urlGenerator)
				{
					return $urlGenerator->resource($path);
				}
			)
		);

		// registering ceil, floor filters
		$this->_twig->addFilter(new \Twig_SimpleFilter('ceil', 'ceil'));
		$this->_twig->addFilter(new \Twig_SimpleFilter('floor', 'floor'));

		// registering number related filters
		$this->_twig->addFilter(new \Twig_SimpleFilter('groupThousands', function($val, $decPoint = '.', $thousandSep = ',') {
				$tmp = explode('.', $val);
				$decimals = count($tmp) > 1 ? strlen($tmp[count($tmp) - 1]) : 0;
				return number_format($val, $decimals, $decPoint, $thousandSep);
			})
		);

		// registering date filter
		$this->_twig->addFilter(
			new \Twig_SimpleFilter(
				'dateFromString',
				function($date, $format = 'l, jS F, Y')
				{
					return date($format, strtotime($date));
				}
			)
		);
	}

	/**
	 * Assigns a variable that is accessible in template file.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function assign($name, $value)
	{
		$this->_data[$name] = $value;
	}

	/**
	 * Gets an assigned variable.
	 *
	 * @param string $name
	 * @param null $default
	 * @return null|mixed
	 */
	public function get($name, $default = null)
	{
		return isset($this->_data[$name]) ? $this->_data[$name] : $default;
	}

	/**
	 * Generates template output.
	 *
	 * @return string
	 */
	public function generate()
	{
		if (!$this->_templatePath)
			$this->_templatePath = 'default.twig';

		return $this->_twig->render($this->_templatePath, $this->_data);
	}

	/**
	 * Sets template path.
	 *
	 * @param string $path
	 */
	public function setTemplatePath($path)
	{
		$this->_templatePath = $path;
	}
}