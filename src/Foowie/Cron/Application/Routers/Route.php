<?php

namespace Foowie\Cron\Application\Routers;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;
use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Route extends \Nette\Application\Routers\Route {

	public static function prependToRouteList(RouteList $routeList, IRouter $router) {
		$count = count($routeList);
		foreach($routeList as $routeId => $route) {
			if($routeId == $count - 2) {
				$routeList[] = $route;
				break;
			}
			$routeList[$routeId + 1] = $route;
		}
		$routeList[0] = $router;
	}

} 