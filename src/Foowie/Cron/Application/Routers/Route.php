<?php

namespace Foowie\Cron\Application\Routers;

use Nette\Routing\Router;
use Nette\Application\Routers\RouteList;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Route extends \Nette\Application\Routers\Route {

	public static function prependToRouteList(RouteList $routeList, Router $router, int $flags = 0) {
		$routeList->prepend($router, $flags);
	}

} 