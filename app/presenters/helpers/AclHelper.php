<?php

namespace PresenterHelpers;

use Nette\Security\User;


class AclHelper extends BaseHelper
{
	/** @var User */
	protected $user;



	const INACTIVITY = 1;
	const LOGIN_REQUIRED = 2;
	const NOT_ALLOWED = 4;



	/**
	 * @param  User
	 * @return void
	 */
	function inject(User $user)
	{
		$this->user = $user;
	}



	/** @return int */
	function checkPrivileges()
	{
		$flag = 0;

		$presenter = $this->presenter;
		$name = $presenter->name;
		$action = $presenter->action;
		$signal = $presenter->signal;

		if (!$this->user->loggedIn && $this->user->logoutReason === User::INACTIVITY && $action !== 'logout') { // just inform the user about the logout
			$flag |= static::INACTIVITY;
		}

		if (!$this->user->isAllowed($name, $action) || ($signal !== NULL && !$this->user->isAllowed( $name, $this->formatSignalString($signal) ))) {
			if (!$this->user->loggedIn) {
				$flag |= static::LOGIN_REQUIRED;

			} else {
				$flag |= static::NOT_ALLOWED;
			}
		}

		return $flag;
	}



	/**
	 * @param  string|NULL
	 * @return string|NULL
	 */
	protected function formatSignalString($signal)
	{
		return $signal === NULL ? NULL : ltrim(implode('-', $signal), '-') . '!';
	}
}
