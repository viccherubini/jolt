<?php

namespace JoltTest;
use \Jolt\Route_Named;

require_once 'Jolt/Route/Named.php';

class JoltCore_Route_Route_NamedTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = new Route_Named(NULL, 'Controller', 'Action');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteMustHaveController() {
		$route = new Route_Named('/users/', NULL, 'Action');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteMustHaveAction() {
		$route = new Route_Named('/users/', 'Controller', NULL);
	}
	
	
	public function testRouteIsSet() {
		$route = new Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('/route/', $route->getRoute());
	}
	
	public function testControllerIsSet() {
		$route = new Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Controller', $route->getController());
	}
	
	public function testActionIsSet() {
		$route = new Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Action', $route->getAction());
	}
	
	/**
	 * @dataProvider providerValidRoute
	 */
	public function testRouteNameIsValid($route_name) {
		$route = new Route_Named('/', 'Controller', 'Action');
		
		$this->assertTrue($route->setRoute($route_name)->isValid());
	}
	
	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function testRouteNameIsInvalid($route_name) {
		$route = new Route_named('/', 'Controller', 'Action');
	
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