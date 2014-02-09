<?php

namespace Naga\Core\Model;

use Naga\Core\nComponent;

abstract class Model extends nComponent
{
	private $_data = array();
	protected $_fieldMap = array();
	private $_db;

	public function __construct($id, $db, $load = true)
	{
		$this->_data['id'] = $id;
		$this->_db = $db;
		if ($load)
			$this->load();
	}

	public abstract function load();
	public abstract function save();
	public abstract function delete();
	public abstract function create();

	public function db()
	{
		return $this->_db;
	}

	public function id()
	{
		return $this->id;
	}

	public function mergeFrom(array $properties)
	{
		// filtering id
		if (isset($properties['id']))
			unset($properties['id']);

		$this->_data = array_merge($this->_data, $properties);
		return $this;
	}

	public function __get($property)
	{
		if (isset($this->_fieldMap[$property]))
			$property = $this->_fieldMap[$property];

		return isset($this->_data[$property]) ? $this->_data[$property] : null;
	}

	public function __set($property, $value)
	{
		if ($property == 'id' && $this->_data['id'] != 0)
			throw new \Exception("You can't change a model's id.");

		$this->_data[$property] = $value;
		return $this;
	}

	public function __isset($property)
	{
	  	return isset($this->_data[$property]) || isset($this->_fieldMap[$property]);
	}
}