<?php

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\SimpleRouter;


class RouterFactory extends Nette\Object
{
	/** @return \Nette\Application\IRouter */
	function createRouter()
	{
		$router = new RouteList;

		$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
		$router[] = new Route('<presenter>[/<action>][/<id>]', 'Homepage:default');
		$router[] = new SimpleRouter('Homepage:default');

		return $router;
	}
}
