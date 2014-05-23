<?php

namespace Naga\Core\Database\Orm;

use Naga\Core\iComponent;

interface iQueryBuilder extends iComponent
{
	// data definition statements
	public function createDatabase($database);
	public function dropDatabase($database);
	public function alterDatabase($database);

	public function createTable($table);
	public function dropTable($table);
	public function truncateTable($table);
	public function alterTable($table);
	public function renameTable($table);

	// data manipulation statements
	public function table($table);
	public function select(array $columns);
	public function update(array $data);
	public function delete();
	public function insert(array $columns);

	// joins
	public function innerJoin($target);
	public function leftJoin($target);
	public function rightJoin($target);
	public function join($target);

	// conditions
	public function condition($first, $operand, $second);
	public function orCondition($first, $operand, $second);
	public function equals($first, $second);
	public function orEquals($first, $second);
	public function greaterThan($first, $second);
	public function orGreaterThan($first, $second);
	public function smallerThan($first, $second);
	public function orSmallerThan($first, $second);
	public function between($what, $from, $to);
	public function orBetween($what, $from, $to);
	public function in($what, array $list);
	public function orIn($what, array $list);
	public function exists($query);
	public function orExists($query);

	// grouping
	public function groupStart($name);
	public function groupEnd($name);

	// get results
	public function execute();
	public function generate();
}