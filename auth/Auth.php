<?php

namespace Naga\Core\Auth;

use Naga\Core\Exception;
use Naga\Core\Session\Storage\iSessionStorage;
use Naga\Core\nComponent;

/**
 * Basic authentication class.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Auth
 */
class Auth extends nComponent
{
	/**
	 * @var array
	 */
	private $_loggedInInstances = array();
	/**
	 * @var mixed
	 */
	private $_defaultUserId;

	/**
	 * Construct. Creates User instances from session data.
	 *
	 * @param iSessionStorage $session
	 */
	public function __construct(iSessionStorage $session)
	{
		$this->registerComponent('session', $session);
		$users = $this->session()->get('loggedInData');
		if (!$users)
			return;

		if (!is_array($users))
			throw new Exception\AuthException('Auth init failed: corrupted session data.');

		foreach ($users as $id => $user)
		{
			$instance = new User($id);
			$instance->mergeWith($user['data']);
			$this->addUserInstance($instance, $user['isDefault']);
		}
	}

	/**
	 * Gets the User instance of user $id.
	 *
	 * @param mixed $id
	 * @return User
	 * @throws \Naga\Core\Exception\AuthException
	 */
	public function user($id = 0)
	{
		if (!$id)
			$user = $this->defaultUser();
		else
		{
			if (!isset($this->_loggedInInstances[$id]))
				throw new Exception\AuthException("Can't get user with id {$id}.");

			$user = $this->_loggedInInstances[$id];
		}

		return $user;
	}

	/**
	 * Gets the default user instance.
	 *
	 * @return User
	 */
	public function defaultUser()
	{
		return $this->_loggedInInstances[$this->_defaultUserId];
	}

	/**
	 * Returns whether the user is logged in as user $id.
	 *
	 * @param mixed $id
	 * @return bool
	 */
	public function isLoggedInAs($id)
	{
		return isset($this->_loggedInInstances[$id]);
	}

	/**
	 * Returns whether the user is logged in. Use isLoggedInAs($id) to determine
	 * if the user is logged in as $id.
	 *
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return count($this->_loggedInInstances) > 0;
	}

	/**
	 * Creates a User instance with the specified data. You can use $condition for special condition(s),
	 * like user is trying to login from a trusted device or not, etc.
	 * Example:
	 * Auth->loginAs(1, array('username' => 'Somebody'), isset($trustedDevices[$userDevice]), true)
	 *
	 * @param mixed $id
	 * @param array $data
	 * @param bool $condition special condition
	 * @param bool $setDefault
	 * @return bool
	 */
	public function loginAs($id, $data = array(), $condition = true, $setDefault = true)
	{
		if (!$condition)
			return false;

		$user = new User($id);
		$user->mergeWith($data);
		$this->addUserInstance($user, $setDefault);

		return true;
	}

	/**
	 * Clears logged in users data.
	 */
	public function logout()
	{
		$this->_loggedInInstances = array();
		$this->_defaultUserId = null;
		$this->session()->clear();
		$this->session()->end();
	}

	/**
	 * Logs out the user with the specified id.
	 *
	 * @param $id
	 */
	public function logoutAs($id)
	{
		if (isset($this->_loggedInInstances[$id]))
			unset($this->_loggedInInstances[$id]);

		if ($this->_defaultUserId === $id)
		{
			$ids = array_keys($this->_loggedInInstances);
			if (count($ids))
				$this->_defaultUserId = $ids[0];
		}
		$this->storeSessionData();
	}

	/**
	 * Adds an initialized user instance.
	 *
	 * @param User $user
	 * @param bool $setDefault
	 */
	public function addUserInstance(User $user, $setDefault = false)
	{
		$this->_loggedInInstances[$user->id()] = $user;
		if ($setDefault)
			$this->setDefaultUser($user->id());
	}

	/**
	 * Sets the default user. This is practical if you don't want to specify the user id
	 * when using Auth->user().
	 *
	 * @param $id
	 */
	public function setDefaultUser($id)
	{
		$this->_defaultUserId = $id;
	}

	/**
	 * Stores the auth data in session. Don't forget to call it before use exit().
	 * Application->redirect() automatically calls this method.
	 */
	public function storeSessionData()
	{
		$data = array();
		foreach ($this->_loggedInInstances as $id => $instance)
		{
			$data[$id] = array(
				'data' => $instance->toArray(),
				'isDefault' => $id == $this->_defaultUserId ? true : false
			);
		}
		$this->session()->set('loggedInData', $data);
	}

	/**
	 * Gets the session instance.
	 *
	 * @return iSessionStorage
	 */
	protected function session()
	{
		return $this->component('session');
	}
}