<?php

namespace Naga\Core\Debug;

use Naga\Core\nComponent;

class Profiler extends nComponent implements iProfiler
{
	/**
	 * @var bool enable profiling?
	 */
	private static $_enabled = false;

	/**
	 * @var string Profiler instance name
	 */
	private $_name;

	/**
	 * @var Timer[] Timer instances
	 */
	private $_timers = array();

	/**
	 * Construct.
	 *
	 * @param   string  $name
	 * @throws  \Exception
	 */
	public function __construct($name)
	{
		if (is_array($name) || !(string)$name)
			throw new \Exception('Invalid name specified for Profiler instance: ' . gettype($name));

		$this->_name = (string)$name;
	}

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
	public function createTimer($name, $start = true, $overwrite = true)
	{
		if (!self::$_enabled)
			return $this;

		if (isset($this->_timers[$name]))
		{
			if ($overwrite)
				$this->_timers[$name]->reset();
			else
				trigger_error("Created timer overwrites existing: {$name}", E_USER_NOTICE);
		}
		else
			$this->_timers[$name] = new Timer($name);

		if ($start)
			$this->_timers[$name]->start();

		return $this;
	}

	/**
	 * Starts a timer.
	 *
	 * @param string $name
	 * @return Profiler
	 */
	public function startTimer($name)
	{
		if (self::$_enabled)
			$this->timer($name)->start();

		return $this;
	}

	/**
	 * Pause a timer.
	 *
	 * @param string $name
	 * @return Profiler
	 */
	public function pauseTimer($name)
	{
		if (self::$_enabled)
			$this->timer($name)->pause();

		return $this;
	}

	/**
	 * Stops a timer.
	 *
	 * @param string $name
	 * @return Profiler
	 */
	public function stopTimer($name)
	{
		if (self::$_enabled)
			$this->timer($name)->stop();

		return $this;
	}

	/**
	 * Gets a Timer instance with the specified name.
	 *
	 * @param   string  $name
	 * @return  iTimer
	 * @throws  \Exception
	 */
	protected function timer($name)
	{
		if (!isset($this->_timers[$name]))
			throw new \Exception("Can't get iTimer with name {$name}");

		return $this->_timers[$name];
	}

	/**
	 * Gets all iTimer instances in an array.
	 *
	 * @return iTimer[]
	 */
	public function timers()
	{
		return $this->_timers;
	}

	/**
	 * Gets all iTimer results in an array.
	 *
	 * @param int $measure
	 * @param int $roundPrecision
	 * @return array
	 */
	public function timerResults($measure = Timer::Dynamic, $roundPrecision = 4)
	{
		if (!self::$_enabled)
			return array();

		$results = array();
		foreach ($this->_timers as $timer)
			$results[$timer->name()] = $timer->result($measure, $roundPrecision);

		return $results;
	}

	/**
	 * Gets timer result in specified time measurement.
	 *
	 * @param   string          $name           timer name
	 * @param   int             $measure        time measure
	 * @param   int             $roundPrecision round precision
	 * @return  string|float    result
	 */
	public function timerResult($name, $measure = Timer::Dynamic, $roundPrecision = 4)
	{
		if (!self::$_enabled)
			return null;

		return $this->timer($name)->result($measure, $roundPrecision);
	}

	/**
	 * Enables profiling with iProfiler.
	 */
	public static function enable()
	{
		self::$_enabled = true;
	}

	/**
	 * Disables profiling with iProfiler.
	 */
	public static function disable()
	{
		self::$_enabled = false;
	}
}