<?php

namespace Naga\Core\Database;

use Naga\Core\Database\Connection\MySQL\MySqlConnection;
use Naga\Core\Database\Orm\iQueryBuilder;

class MySqlQueryBuilder extends MySqlConnection implements iQueryBuilder
{
	protected $_query;

	public function createDatabase($database) { }
	public function dropDatabase($database) { }
	public function alterDatabase($database) { }

	public function createTable($table) { }
	public function dropTable($table) { }
	public function truncateTable($table) { }
	public function alterTable($table) { }
	public function renameTable($table) { }

	public function table($table)
	{
		$this->_query['table'] = $table;

		return $this;
	}

	public function select(array $columns)
	{
		$this->_query['select'] = $columns;

		return $this;
	}

	public function update(array $data) { }
	public function delete() { }
	public function insert(array $columns) { }

	public function innerJoin($target) { }
	public function leftJoin($target) { }
	public function rightJoin($target) { }
	public function join($target) { }

	public function condition($first, $operand, $second)
	{
		$this->_query['condition'] = (object)array(
			'first' => $first,
			'operand' => $operand,
			'second' => $second
		);

		return $this;
	}

	public function orCondition($first, $operand, $second)
	{
		$this->_query['orCondition'] = (object)array(
			'first' => $first,
			'operand' =>  $operand,
			'second' => $second
		);

		return $this;
	}

	public function equals($first, $second)
	{
		return $this->condition($first, '=', $second);
	}

	public function orEquals($first, $second)
	{
		return $this->orCondition($first, '=', $second);
	}

	public function greaterThan($first, $second)
	{
		return $this->condition($first, '>', $second);
	}

	public function orGreaterThan($first, $second)
	{
		return $this->orCondition($first, '>', $second);
	}

	public function smallerThan($first, $second)
	{
		return $this->condition($first, '<', $second);
	}

	public function orSmallerThan($first, $second)
	{
		return $this->orCondition($first, '<', $second);
	}

	public function between($what, $from, $to)
	{
		$this->_query['between'] = (object)array(
			'what' => $what,
			'from' => $from,
			'to' => $to
		);

		return $this;
	}

	public function orBetween($what, $from, $to)
	{
		$this->_query['between'] = (object)array(
			'what' => $what,
			'from' => $from,
			'to' => $to
		);

		return $this;
	}

	public function in($what, array $list)
	{
		$this->_query['in'] = (object)array(
			'what' => $what,
			'list' => $list
		);

		return $this;
	}

	public function orIn($what, array $list)
	{
		$this->_query['orIn'] = (object)array(
			'what' => $what,
			'list' => $list
		);

		return $this;
	}

	public function exists($query)
	{
		$this->_query['exists'] = (string)$query;
	}

	public function orExists($query)
	{
		$this->_query['orExists'] = (string)$query;
	}

	public function groupStart($name){ }
	public function groupEnd($name) { }

	public function execute() { }

	public function generate()
	{
		$generated = '';
		$table = '';

		foreach ($this->_query as $operation => $data)
		{
			switch ($operation)
			{
				case 'select':
					$generated = 'select ' . implode(', ', $data) . ' from ' . $table;
					break;
				case 'table':
					$table = $data;
					break;
				default:
					continue;
					break;
			}
		}

		return $generated;
	}
}