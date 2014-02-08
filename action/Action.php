<?php

namespace Naga\Core\Action;

use Naga\Core\View\View;
use Naga\Core\nComponent;

/**
 * Base action class. You can use action classes for business logic.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Action
 */
abstract class Action extends nComponent
{
	private $_params = array();
	private $_view;

	public function __construct(array $params)
	{
		$this->_params = $params;
	}

	public function get($name)
	{
		return isset($this->_params[$name]) ? $this->_params[$name] : null;
	}

	public function set($name, $value)
	{
		$this->_params[$name] = $value;
	}

	public function setView(View $view)
	{
		$this->_view = $view;
	}

	/**
	 * Gets the view instance.
	 *
	 * @return View
	 */
	public function view()
	{
		return $this->_view;
	}

	abstract public function execute();
}