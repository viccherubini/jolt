<?php

declare(encoding='UTF-8');
namespace JoltTest\Route;

use \Jolt\Route\NotFound,
	\JoltTest\TestCase;

require_once 'Jolt/Route.php';
require_once 'Jolt/Route/Named.php';
require_once 'Jolt/Route/NotFound.php';

class NotFoundTest extends TestCase {
	
	public function testGetController_IsAlwaysRouteNotFound() {
		$route = new NotFound;
		$this->assertEquals($route::CONTROLLER_NAME, $route->getController());
	}
	
	public function testGetAction_IsAlwaysIndex() {
		$route = new NotFound;
		$this->assertEquals('index', $route->getAction());
	}
}