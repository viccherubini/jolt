<?php

require_once 'Jolt/Route/Named.php';

class Jolt_Route_NamedTest extends Jolt_TestCase {
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Route_Can_Not_Be_Empty() {
		$route = new Jolt_Route_Named(NULL, 'Controller', 'Action');
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Route_Must_Have_Controller() {
		$route = new Jolt_Route_Named('/users/', NULL, 'Action');
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Route_Must_Have_Action() {
		$route = new Jolt_Route_Named('/users/', 'Controller', NULL);
	}
	
	public function test_Route_Is_Set() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('/route/', $route->getRoute());
	}
	
	public function test_Controller_Is_Set() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Controller', $route->getController());
	}
	
	public function test_Action_Is_Set() {
		$route = new Jolt_Route_Named('/route/', 'Controller', 'Action');
		$this->assertEquals('Action', $route->getAction());
	}
	
	/**
	 * @dataProvider providerValidRoute
	 */
	public function test_Route_Will_Allow_Valid_Routes($route_name) {
		$route = new Jolt_Route_Named('/', 'Controller', 'Action');
		
		$this->assertTrue($route->setRoute($route_name)->isValid());
	}
	
	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function test_Route_Will_Not_Allow_Invalid_Routes($route_name) {
		$route = new Jolt_Route_Named('/', 'Controller', 'Action');
	
		$this->assertFalse($route->setRoute($route_name)->isValid());
	}
	
	/**
	 * @dataProvider providerValidRouteAndUri
	 */
	public function test_Route_Uri_Is_Valid($route_name, $uri) {
		$route = new Jolt_Route_Named($route_name, 'Controller', 'action');
		
		$this->assertTrue($route->isValidUri($uri));
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
			array('/abc/usr/%d/blah/%s'),
			array('/tutorial/%s.html'),
			array('/search/result-%d.html'),
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
			array('/abc/*/d/'),
			array('abc')
		);
	}
	
	public function providerValidRouteAndUri() {
		return array(
			array('/abc', '/abc'),
			array('/user/view', '/user/view'),
			array('/user-long-route', '/user-long-route'),
			array('/abc/usr/%d/blah/%s', '/abc/usr/10/blah/hello'),
			array('/abc/usr/%d/blah/%s', '/abc/usr/1/blah/hello-world'),
			array('/abc/usr/%d/blah/%s', '/abc/usr/1/blah/hello world'),
			array('/tutorial/%s.html', '/tutorial/opengl-tutorial.html'),
			array('/tutorial/%s.html', '/tutorial/the-#named#-tutorial.html'),
			array('/tutorial/%s.html', "/tutorial/a tutorial about %s's.html"),
			array('/user/%d.html', '/user/10.html'),
			array('/user/%d.html', '/user/1.html'),
			array('/search/result-%d.html', '/search/result-10.html'),
			array('/search/result-%d.html', '/search/result-101345.html')
		);
	}
}