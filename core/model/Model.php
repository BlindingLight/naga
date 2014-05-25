<?php

namespace Naga\Core\Model;

use Naga\Core\Application;
use Naga\Core\Collection\Map;

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
	/**
	 * @var string model's table name
	 */
	protected $_table;

	public function __construct($id = null, Application $app, $load = true)
	{
		$this->add('id', $id);
		$this->registerComponent('app', $app);
		if ($id && $load)
			$this->load();
	}

	public function load()
	{
		$query = $this->app()->queryBuilder()->reset();
		$data = $query->table($this->_table)->select()->equals('id', $this->id())->execute(true);
		$this->mergeWith((array)$data);

		return $this;
	}

	public function save()
	{

	}

	public function delete()
	{

	}

	public function create()
	{
		if ($this->id())
			$this->remove('id');

		var_dump($this->toArray());
		$query = $this->app()->queryBuilder()->reset();
		$data = $query->table($this->_table)->insert($this->toArray())->execute();

		return $this;
	}

	/**
	 * Creates database table for model.
	 *
	 * @param array $columns
	 * @param array $settings
	 */
	public function install($columns = array(), $settings = array())
	{
		$query = $this->app()->queryBuilder()->reset();
		$query->createTable($this->_table, $settings, $columns);

		echo $query->generate();
		$query->execute();
	}

	/**
	 * Gets the model's Application instance.
	 *
	 * @return Application
	 */
	public function app()
	{
		return $this->component('app');
	}

	/**
	 * Gets the model's id.
	 *
	 * @return null|string|int
	 */
	public function id()
	{
		return $this->get('id');
	}

	/**
	 * Sets properties from an array. You can't set id with this method.
	 *
	 * @param array $data
	 * @return $this
	 */
	public function mergeWith($data)
	{
		// filtering id
		if (isset($data['id']) && $this->get('id') != 0)
			unset($data['id']);

		parent::mergeWith($data);

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