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
	 * @return  iTimer
	 * @throws  \Exception
	 */
	public function timer($name);

	/**
	 * Gets all iTimer instances in an array.
	 *
	 * @return iTimer[]
	 */
	public function timers();

	/**
	 * Gets all iTimer results in an array.
	 *
	 * @param int $measure
	 * @param int $roundPrecision
	 * @return array
	 */
	public function timerResults($measure = 1, $roundPrecision = 4);
}