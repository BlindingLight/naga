<?php

namespace Naga\Core\Response;

/**
 * Class for creating json responses.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Response
 */
class JsonResponse extends Response implements iResponse
{
	/**
	 * JsonResponse construct. Use it as a Map construct.
	 *
	 * @param array $data
	 * @param bool $readOnly
	 */
	public function __construct($data = array(), $readOnly = false)
	{
		parent::__construct($data, $readOnly);
		$this->setMimeType('application/json');
	}

	/**
	 * Same as \Naga\Core\Collection\Map toArray().
	 *
	 * @return mixed
	 */
	public function data()
	{
		return $this->toArray();
	}

	/**
	 * Echoes the json encoded output, and if $exitAfter is true, calls exit().
	 *
	 * @param bool $exitAfter
	 */
	public function send($exitAfter = false)
	{
		header('Content-type: ' . $this->mimeType());
		echo json_encode($this->data());
		if ($exitAfter)
			exit();
	}
}