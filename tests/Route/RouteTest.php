<?php

require_once 'Jolt/Route.php';

class Jolt_Route_RouteTest extends Jolt_TestCase {
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = $this->buildRouteObject();
		$route->setRoute(NULL);
	}
	
	protected function buildRouteObject() {
		$mock = $this->getMockForAbstractClass('Jolt_Route');
		return $mock;
	}
}