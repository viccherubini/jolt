<?php

namespace JoltTest;
use \Jolt\Route_Restful;

require_once 'Jolt/Route/Restful.php';

class JoltCore_Route_Route_RestfulTest extends TestCase {
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

	public function testRouteIsSet() {
		$route = new Route_Restful('/user', 'Resource');
		$this->assertEquals('/user', $route->getRoute());
	}
	
	
	public function testResourceIsSet() {
		$route = new Route_Restful('/user', 'Resource');
		$this->assertEquals('Resource', $route->getResource());
	}
	
	
}