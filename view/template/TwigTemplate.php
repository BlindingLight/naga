<?php

namespace Naga\Core\View\Template;

use Naga\Core\Localization\Localization;
use Naga\Core\Routing\UrlGenerator;
use Naga\Core\nComponent;

class TwigTemplate extends nComponent implements iTemplate
{
	private $_data = array();
	private $_twig;
	private $_templatePath = '';
	private $_templateRoot = '';

	public function __construct($templateRootDir, $compiledDir, UrlGenerator $urlGenerator, Localization $localization)
	{
		$this->_templateRoot = $templateRootDir . '/';
		$loader = new \Twig_Loader_Filesystem($templateRootDir);
		$this->_twig = new \Twig_Environment(
			$loader,
			array(
				'cache' => $compiledDir,
				'auto_reload' => true
			)
		);
		// registering localize filter
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
		// registering price related filters
		$this->_twig->addFilter(new \Twig_SimpleFilter('groupThousands', function($val) {
				$tmp = explode('.', $val);
				$decimals = count($tmp) > 1 ? strlen($tmp[count($tmp) - 1]) : 0;
				return number_format($val, $decimals, '.', ',');
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

	public function assign($name, $value)
	{
		$this->_data[$name] = $value;
	}

	public function get($name, $default = null)
	{
		return isset($this->_data[$name]) ? $this->_data[$name] : $default;
	}

	public function generate()
	{
		if (!$this->_templatePath)
			$this->_templatePath = 'default.twig';

		return $this->_twig->render($this->_templatePath, $this->_data);
	}

	public function setTemplatePath($path)
	{
		$this->_templatePath = $path;
	}
}