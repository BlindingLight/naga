<?php

namespace Naga\Core\Database;

use Naga\Core\Database\Connection\MySQL\MySqlConnection;
use Naga\Core\Database\iQueryBuilder;

class MySqlQueryBuilder extends MySqlConnection implements iQueryBuilder
{
	protected $_query;

	public function createDatabase($database) { }
	public function dropDatabase($database) { }
	public function alterDatabase($database) { }

	public function createTable($table, $settings, $columns)
	{
		$this->_query['createTable'] = (object)array(
			'table' => $table,
			'settings' => $settings,
			'columns' => $columns
		);
	}

	public function dropTable($table) { }
	public function truncateTable($table) { }
	public function alterTable($table) { }
	public function renameTable($table) { }

	public function table($table)
	{
		$this->_query['table'] = $table;

		return $this;
	}

	public function select()
	{
		$this->_query['select'] = func_get_args();

		return $this;
	}

	public function update(array $data) { }
	public function delete() { }

	public function insert(array $columns)
	{
		$this->_query['insert'] = $columns;

		return $this;
	}

	public function innerJoin($target) { }
	public function leftJoin($target) { }
	public function rightJoin($target) { }
	public function join($target) { }

	public function condition($first, $operator, $second)
	{
		$this->_query['condition'] = (object)array(
			'first' => $first,
			'operator' => $operator,
			'second' => $second
		);

		return $this;
	}

	public function orCondition($first, $operator, $second)
	{
		$this->_query['orCondition'] = (object)array(
			'first' => $first,
			'operator' =>  $operator,
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

	public function exists($callback)
	{
		$query = clone $this;
		$query->reset();

		if (is_callable($callback))
			$this->_query['exists'] = $callback($query);
		else
			$this->_query['exists'] = (string)$callback;
	}

	public function orExists($callback)
	{
		$query = clone $this;
		$query->reset();

		if (is_callable($callback))
			$this->_query['exists'] = $callback($query);
		else
			$this->_query['exists'] = (string)$callback;
	}

	public function groupStart($name){ }
	public function groupEnd($name) { }

	public function reset()
	{
		$this->_query = array();

		return $this;
	}

	public function execute($oneRow = false)
	{
		$params = array();
		foreach ($this->_query as $operation => $data)
		{
			if (in_array($operation, array('condition', 'orCondition')))
			{
				$id = md5($data->second);
				$params[":{$id}"] = $data->second;
			}
			else if ($operation == 'insert')
			{
				foreach ($data as $colName => $val)
					$params[":{$colName}"] = $val;
			}
		}

		return !$oneRow ? $this->query($this->generate(), $params) : $this->queryOne($this->generate(), $params);
	}

	public function generate()
	{
		$generated = '';
		$table = '';

		$previousOperation = '';
		foreach ($this->_query as $operation => $data)
		{
			switch ($operation)
			{
				case 'select':
					$columns = is_array($data) && count($data) ? implode(', ', $data) : '*';
					$generated = "select {$columns}\nfrom {$table}\n";
					break;
				case 'table':
					$table = "`{$data}`";
					break;
				case 'condition':
					$id = md5($data->second);
					if ($previousOperation == 'select')
						$generated .= "where (\n`{$data->first}` {$data->operator} :{$id}\n";
					else
						$generated .= "and `{$data->first}` {$data->operator} :{$id}\n";
					break;
				case 'orCondition':
					$id = md5($data->second);
					if ($previousOperation == 'select')
						$generated .= "where (\n`{$data->first}` {$data->operator} :{$id}\n";
					else
						$generated .= "or `{$data->first}` {$data->operator} :{$id}\n";
					break;
				case 'exists':
					$data = $data instanceof iQueryBuilder ? $data->generate() : $data;
					if ($previousOperation == 'select')
						$generated .= "where (\nexists(\n{$data}\n)\n";
					else
						$generated .= "and exists(\n{$data}\n)\n";
					break;
				case 'createTable':
					$generated = $this->generateCreateTable($data->table, $data->settings, $data->columns);
					break;
				case 'insert':
					$generated = $this->generateInsert($table, $data);
					break;
				default:
					continue;
					break;
			}

			$previousOperation = $operation;
		}

		return $generated . (in_array($previousOperation, array('condition', 'orCondition', 'exists', 'orExists')) ? ')' : '');
	}

	protected function generateCreateTable($table, $settings, $columns)
	{
		$engine = isset($settings['engine']) ? $settings['engine'] : 'InnoDB';
		$generated = "create table `{$table}` (\n";
		$current = 0;
		foreach ($columns as $name => $data)
		{
			$first = !$current;
			$type = isset($data->primary) && $data->primary ? 'bigint' : $data->type;
			$length = isset($data->length) && $data->length ? "({$data->length}) " : ' ';
			$primary = isset($data->primary) && $data->primary ? ", primary key(`{$name}`) " : ' ';
			$notNull = isset($data->null) && !$data->null ? ' not null ' : '';
			$unique = isset($data->unique) && $data->unique ? ", unique(`{$name}`) " : '';
			$index = !$unique && isset($data->index) && $data->index ? ", index using {$data->index}(`{$name}`) " : ' ';
			$autoIncrement = isset($data->autoIncrement) && $data->autoIncrement ? ' auto_increment ' : ' ';
			$unsigned = isset($data->unsigned) && $data->unsigned ? ' unsigned ' : ' ';
			$generated .= (!$first ? ', ' : '') . "`{$name}` {$type}{$length}{$unsigned}{$notNull}{$autoIncrement}{$unique}{$index}{$primary}";
			++$current;
		}

		$generated .= "\n) ENGINE = {$engine}";

		return $generated;
	}

	protected function generateInsert($table, $columns)
	{
		$columnNames = array_keys($columns);
		foreach ($columnNames as &$col)
			$col = "`{$col}`";

		$columnNames = implode(', ', $columnNames);

		$data = array_keys($columns);
		foreach ($data as &$col)
			$col = ":{$col}";

		$data = implode(', ', $data);


		$generated = "insert into {$table} ({$columnNames}) values ({$data})";

		var_dump($generated);

		return $generated;
	}
}