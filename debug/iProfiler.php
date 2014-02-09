<?php

namespace Naga\Core\Debug;

interface iProfiler
{
	/**
	 * Creates a timer with the specified name. Also starts it if $start = true.
	 *
	 * @param   string  $name
	 * @param   bool    $start
	 */
	public function createTimer($name, $start = true);

	/**
	 * Gets a Timer instance with the specified name.
	 *
	 * @param   string  $name
	 * @return  Timer
	 * @throws  \Exception
	 */
	public function timer($name);
}