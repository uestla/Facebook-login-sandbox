<?php

namespace Model\Services;

use Nette;
use Nette\Security as NS;
use Nette\Database as DB;


/*

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `facebook_id` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `birthday` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`facebook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

 */


class FacebookService extends Nette\Object implements NS\IAuthenticator
{
	/** @var DB\Connection */
	protected $db;



	/** @param  DB\Connection */
	function __construct(DB\Connection $connection)
	{
		$this->db = $connection;
	}



	/**
	 * @param  array
	 * @return NS\Identity
	 */
	function authenticate(array $credentials)
	{
		list ($user) = $credentials;

		$this->db->beginTransaction();

			$row = $this->getTable()->get($user['id']);

			if ($row === FALSE) {
				$row = $this->getTable()->insert(array(
					'facebook_id' => $user['id'],
					'name' => $user['name'],
					'email' => $user['email'],
					'birthday' => $user['birthday'],
				));

			} else {
				$row->update(array(
					'name' => $user['name'],
					'email' => $user['email'],
					'birthday' => $user['birthday'],
				));
			}

		$this->db->commit();

		return new NS\Identity($user['id'], 'user', $row->toArray());
	}



	/** @return DB\Table\Selection */
	protected function getTable()
	{
		return $this->db->table('user');
	}
}
