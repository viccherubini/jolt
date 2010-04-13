<?php

namespace JoltTest;
use \Jolt\Route;

require_once 'Jolt/Route.php';

class JoltCore_Route_RouteTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = $this->buildRouteObject();
		$route->setRoute(NULL);
	}
	
	protected function buildRouteObject() {
		$mock = $this->getMockForAbstractClass('\Jolt\Route');
		return $mock;
	}
}