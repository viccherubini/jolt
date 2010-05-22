<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Route/Restful.php';

class Route_RestfulTest extends TestCase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = new Route_Restful(NULL, 'Resource');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteMustHaveResource() {
		$route = new Route_Restful('/user', NULL);
	}

	public function testRouteIsSetCorrectly() {
		$route = new Route_Restful('/user', 'Resource');
		$this->assertEquals('/user', $route->getRoute());
	}
	
	public function testResourceIsSetCorrectly() {
		$route = new Route_Restful('/user', 'Resource');
		$this->assertEquals('Resource', $route->getResource());
	}
}