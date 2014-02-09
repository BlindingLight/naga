<?php

namespace Naga\Core\Debug;

interface iTimer
{
	/**
	 * Gets timer result in specified time measurement. If $measure = Timer::Dynamic, result is returned as
	 * a string with the biggest possible measure that have value bigger or equal to 1.
	 *
	 * @param   int             $measure    time measure
	 * @param   int             $roundPrecision  round precision
	 * @return  string|float    result
	 */
	public function result($measure = 1, $roundPrecision = 4);

	/**
	 * Starts the timer. Returns with iTimer instance for chainability.
	 *
	 * @return iTimer
	 */
	public function start();

	/**
	 * Stops the timer. Returns with iTimer instance for chainability.
	 *
	 * @return iTimer
	 */
	public function stop();

	/**
	 * Pauses the timer. Returns with iTimer instance for chainability.
	 *
	 * @return iTimer
	 */
	public function pause();

	/**
	 * Gets timer name.
	 *
	 * @return string
	 */
	public function name();

	/**
	 * Gets timer state.
	 *
	 * @return int
	 */
	public function state();
}