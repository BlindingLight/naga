<?php

namespace Naga\Core\View;

use Naga\Core\Response\HtmlResponse;
use Naga\Core\Response\JsonResponse;
use Naga\Core\Response\Response;
use Naga\Core\View\Template\iTemplate;
use Naga\Core\View\Template\TwigTemplate;
use Naga\Core\nComponent;

/**
 * Base class for views.
 *
 * @package Naga\Core\View
 * @author  BlindingLight<bloodredshade@gmail.com>
 */
class View extends nComponent
{
	/**
	 * @var \Naga\Core\View\Template\iTemplate|\Naga\Core\View\Template\TwigTemplate
	 */
	private $_template;

	/**
	 * @var \Naga\Core\Response\Response|\Naga\Core\Response\HtmlResponse|\Naga\Core\Response\JsonResponse
	 */
	private $_response;

	/**
	 * Construct.
	 *
	 * @param Response $response
	 * @param iTemplate $template
	 */
	public function __construct(Response $response, iTemplate $template = null)
	{
		$this->setResponse($response);
		if ($template)
			$this->setTemplate($template);
	}

	/**
	 * Executes view.
	 */
	public function execute()
	{
		if ($this->template() && $this->_response instanceof HtmlResponse)
			$this->_response->setContent($this->template()->generate());
		else if ($this->template() && $this->_response instanceof JsonResponse)
			$this->_response->add('content', $this->template()->generate());

		$this->_response->send(true);
	}

	/**
	 * Sets the app's response object.
	 *
	 * @param \Naga\Core\Response\Response $response
	 */
	public function setResponse(Response $response)
	{
		$this->_response = $response;
	}

	/**
	 * Gets the app's resonse object.
	 *
	 * @return \Naga\Core\Response\Response
	 */
	public function response()
	{
		return $this->_response;
	}

	/**
	 * Sets the view's template instance.
	 *
	 * @param iTemplate $template
	 */
	public function setTemplate(iTemplate $template)
	{
		$this->_template = $template;
	}

	/**
	 * Gets the view's template instance.
	 *
	 * @return iTemplate|TwigTemplate
	 */
	public function template()
	{
		return $this->_template;
	}

	/**
	 * Gets an assigned property from template.
	 *
	 * @param string $property
	 * @return mixed|null
	 */
	public function get($property)
	{
		return $this->template()->get($property);
	}

	/**
	 * Assigns a property for template.
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return $this
	 */
	public function assign($property, $value)
	{
		$this->template()->assign($property, $value);

		return $this;
	}
}