<?php

namespace Naga\Core\Model;

use Naga\Core\Collection\Map;
use Naga\Core\Database\Connection\CacheableDatabaseConnection;

/**
 * Abstract class for creating models.
 *
 * @package Naga\Core\Model
 * @author  BlindingLight<bloodredshade@gmail.com>
 */
abstract class Model extends Map
{
	/**
	 * @var array data key -> database field key map
	 */
	protected $_fieldMap = array();

	public function __construct($id = null, CacheableDatabaseConnection $db, $load = true)
	{
		$this->add('id', $id);
		$this->registerComponent('database', $db);
		if ($id && $load)
			$this->load();
	}

	public abstract function load();
	public abstract function save();
	public abstract function delete();
	public abstract function create();

	/**
	 * Gets the model's CacheableDatabaseConnection instance.
	 *
	 * @return CacheableDatabaseConnection
	 */
	public function db()
	{
		return $this->component('database');
	}

	/**
	 * Gets the model's id.
	 *
	 * @return null|string
	 */
	public function id()
	{
		return $this->get('id');
	}

	/**
	 * Sets properties from an array.
	 *
	 * @param array $properties
	 * @return $this
	 */
	public function mergeWith(array $properties)
	{
		// filtering id
		if (isset($properties['id']))
			unset($properties['id']);

		parent::mergeWith($properties);

		return $this;
	}

	/**
	 * Gets a property.
	 *
	 * @param $property
	 * @return null|mixed
	 */
	public function __get($property)
	{
		if (isset($this->_fieldMap[$property]))
			$property = $this->_fieldMap[$property];

		return parent::get($property);
	}

	/**
	 * Sets a property.
	 *
	 * @param $property
	 * @param $value
	 * @return $this
	 * @throws \Exception
	 */
	public function __set($property, $value)
	{
		if ($property == 'id' && $this->get('id') != 0)
			throw new \Exception("You can't change a model's id.");

		parent::add($property, $value);

		return $this;
	}

	/**
	 * Tells whether a property exists.
	 *
	 * @param $property
	 * @return bool
	 */
	public function __isset($property)
	{
	  	return parent::offsetExists($property);
	}
}