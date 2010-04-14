<?php

require_once 'Jolt/Route/Named.php';

class Jolt_Route_NamedTest extends Jolt_TestCase {
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = new Jolt_Route_Named(NULL, 'Controller', 'Action');
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteMustHaveController() {
		$route = new Jolt_Route_Named('/users/', NULL, 'Action');
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouteMustHaveAction() {
		$route = new Jolt_Route_Named('/users/', 'Controller', NULL);
	}
	
	public function testRouteIsSet() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('/route/', $route->getRoute());
	}
	
	public function testControllerIsSet() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Controller', $route->getController());
	}
	
	public function testActionIsSet() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Action', $route->getAction());
	}
	
	/**
	 * @dataProvider providerValidRoute
	 */
	public function testRouteNameIsValid($route_name) {
		$route = new Jolt_Route_Named('/', 'Controller', 'Action');
		
		$this->assertTrue($route->setRoute($route_name)->isValid());
	}
	
	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function testRouteNameIsInvalid($route_name) {
		$route = new Jolt_Route_Named('/', 'Controller', 'Action');
	
		$this->assertFalse($route->setRoute($route_name)->isValid());
	}
	
	public function providerValidRoute() {
		return array(
			array('/'),
			array('/abc'),
			array('/abc9'),
			array('/long_route'),
			array('/long-route'),
			array('/abc99'),
			array('/long_route/'),
			array('/abc/'),
			array('/abc0/'),
			array('/abc/def'),
			array('/abc/def/'),
			array('/abc/%d/'),
			array('/abc/def/efg/%d'),
			array('/abc/def/%s/%d'),
			array('/abc.def/%s/%d'),
			array('/abc./'),
			array('/abc.')
		);
	}
	
	public function providerInvalidRoute() {
		return array(
			array('//'),
			array('///'),
			array('/abc/*'),
			array('/abc/*/'),
			array('/abc/*/d'),
			array('/abc/*/d/')
		);
	}
}