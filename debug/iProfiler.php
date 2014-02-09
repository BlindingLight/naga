<?php

namespace Naga\Core\Debug;

interface iProfiler
{
	/**
	 * Enables profiling with iProfiler.
	 */
	public static function enable();

	/**
	 * Disables profiling with iProfiler.
	 */
	public static function disable();

	/**
	 * Creates a timer with the specified name. Also starts it if $start = true.
	 * If there is an existing timer with $name and $overwrite = true, resets it silently,
	 * else triggers an E_USER_NOTICE level error.
	 *
	 * @param   string  $name
	 * @param   bool    $start start the timer?
	 * @param   bool    $overwrite overwrite existing timer?
	 * @return  iProfiler
	 */
	public function createTimer($name, $start = true, $overwrite = true);

	/**
	 * Starts a timer.
	 *
	 * @param string $name
	 * @return iProfiler
	 */
	public function startTimer($name);

	/**
	 * Pause a timer.
	 *
	 * @param string $name
	 * @return iProfiler
	 */
	public function pauseTimer($name);

	/**
	 * Stops a timer.
	 *
	 * @param string $name
	 * @return iProfiler
	 */
	public function stopTimer($name);

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

	/**
	 * Gets timer result in specified time measurement.
	 *
	 * @param   string          $name           timer name
	 * @param   int             $measure        time measure
	 * @param   int             $roundPrecision round precision
	 * @return  string|float    result
	 */
	public function timerResult($name, $measure = Timer::Dynamic, $roundPrecision = 4);
}