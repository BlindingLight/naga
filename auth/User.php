<?php

namespace Naga\Core\Auth;

use Naga\Core\Collection\Map;
use Naga\Core\Session\Storage\iSessionStorage;
use Naga\Core\nComponent;

/**
 * Basic 'model' for user authentication.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Auth
 */
class User extends Map
{
	/**
	 * Construct.
	 *
	 * @param mixed $id
	 */
	public function __construct($id)
	{
		$this->add('id', $id);
	}

	/**
	 * Gets the user's id.
	 *
	 * @return mixed
	 */
	public function id()
	{
		return $this->get('id');
	}
}