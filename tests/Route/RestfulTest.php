<?php

require_once 'Jolt/Route/Restful.php';

class Jolt_Route_RestfulTest extends Jolt_TestCase {

	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = new Jolt_Route_Restful(NULL, 'Resource');
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteMustHaveResource() {
		$route = new Jolt_Route_Restful('/user', NULL);
	}

	public function testRouteIsSet() {
		$route = new Jolt_Route_Restful('/user', 'Resource');
		$this->assertEquals('/user', $route->getRoute());
	}
	
	public function testResourceIsSet() {
		$route = new Jolt_Route_Restful('/user', 'Resource');
		$this->assertEquals('Resource', $route->getResource());
	}
}