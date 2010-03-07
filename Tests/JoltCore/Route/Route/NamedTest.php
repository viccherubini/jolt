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
	
	public function testRouteNamesAreValid() {
		$route = new Route_Named('/', 'Controller', 'Action');
		
		$this->assertTrue($route->setRoute('/')->isValid());
		$this->assertTrue($route->setRoute('/abc')->isValid());
		$this->assertTrue($route->setRoute('/abc9')->isValid());
		$this->assertTrue($route->setRoute('/long_route')->isValid());
		$this->assertTrue($route->setRoute('/long-route')->isValid());
		$this->assertTrue($route->setRoute('/abc99')->isValid());
		$this->assertTrue($route->setRoute('/long_route/')->isValid());
		$this->assertTrue($route->setRoute('/abc/')->isValid());
		$this->assertTrue($route->setRoute('/abc0/')->isValid());
		$this->assertTrue($route->setRoute('/abc/def')->isValid());
		$this->assertTrue($route->setRoute('/abc/def/')->isValid());
		$this->assertTrue($route->setRoute('/abc/%d/')->isValid());
		$this->assertTrue($route->setRoute('/abc/def/efg/%d')->isValid());
		$this->assertTrue($route->setRoute('/abc/def/%s/%d')->isValid());
		$this->assertTrue($route->setRoute('/abc.def/%s/%d')->isValid());
		$this->assertTrue($route->setRoute('/abc./')->isValid());
		$this->assertTrue($route->setRoute('/abc.')->isValid());
		
		$this->assertFalse($route->setRoute('//')->isValid());
		$this->assertFalse($route->setRoute('///')->isValid());
		$this->assertFalse($route->setRoute('/abc/*')->isValid());
		$this->assertFalse($route->setRoute('/abc/*/')->isValid());
		$this->assertFalse($route->setRoute('/abc/*/d')->isValid());
		$this->assertFalse($route->setRoute('/abc/*/d/')->isValid());
	}
}