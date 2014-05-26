<?php

namespace Naga\Core\Model;

class User extends Model
{
	protected $_table = 'users';

	public function save()
	{
		parent::save();
	}

	public function delete()
	{
		parent::delete();
	}

	public function load()
	{
		return parent::load();
	}

	public function create()
	{
		return parent::create();
	}

	public function install($columns = array(), $settings = array())
	{
		parent::install(
			array_merge(
				array(
					'id' => (object)array(
					    'primary' => true,
					    'autoIncrement' => true,
						'unsigned' => true,
					),
					'username' => (object)array(
					    'type' => 'varchar',
					    'length' => '50',
					    'unique' => true,
					    'index' => 'btree',
						'null' => false
					),
					'email' => (object)array(
					    'type' => 'varchar',
					    'length' => '265',
					    'unique' => true,
					    'index' => 'btree',
						'null' => false
					),
					'password' => (object)array(
					    'type' => 'varchar',
					    'length' => '40',
						'null' => false
					),
					'rememberHash' => (object)array(
					    'type' => 'varchar',
					    'length' => '40',
					    'unique' => true,
					    'index' => 'btree',
						'null' => false
					),
					'salt' => (object)array(
					    'type' => 'varchar',
					    'length' => '50',
					    'unique' => true,
					    'index' => 'btree',
						'null' => false
					),
			    ),
				$columns
			),
			$settings
		);
	}
}