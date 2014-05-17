<?php

namespace Naga\Core\Util;

use Naga\Core\nComponent;

/**
 * Basic string related functionality.
 *
 * @package Naga\Core\Util
 * @author  BlindingLight<bloodredshade@gmail.com>
 */
class String extends nComponent
{
	/**
	 * Gets string length.
	 *
	 * @param string $string
	 * @param string $encoding
	 * @return int
	 */
	public static function length($string, $encoding = 'UTF-8')
	{
		return mb_strlen($string, $encoding);
	}

	/**
	 * Gets a part of a string.
	 *
	 * @param string $string
	 * @param int $offset
	 * @param int $length
	 * @param string $encoding
	 * @return string
	 */
	public static function substring($string, $offset, $length, $encoding = 'UTF-8')
	{
		return mb_substr($string, $offset, $length, $encoding);
	}
}