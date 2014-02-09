<?php

namespace Naga\Core\Debug;

use Naga\Core\nComponent;

class Profiler extends nComponent implements iProfiler
{
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
	 *
	 * @param   string  $name
	 * @param   bool    $start
	 */
	public function createTimer($name, $start = true)
	{
		if (array_key_exists($name, $this->_timers))
			trigger_error("Created timer overwrites existing: {$name}", E_USER_NOTICE);

		$this->_timers[$name] = new Timer($name);
		if ($start)
			$this->_timers[$name]->start();
	}

	/**
	 * Gets a Timer instance with the specified name.
	 *
	 * @param   string  $name
	 * @return  Timer
	 * @throws  \Exception
	 */
	public function timer($name)
	{
		if (!array_key_exists($name, $this->_timers))
			throw new \Exception("Can't get Timer with name {$name}");

		return $this->_timers[$name];
	}

	/**
	 * Gets all Timer instances in an array.
	 *
	 * @return Timer[]
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
		$results = array();
		foreach ($this->_timers as $timer)
			$results[$timer->name()] = $timer->result($measure, $roundPrecision);

		return $results;
	}
}