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
	public function test_Route_Will_Allow_Valid_Uris($route_name, $uri) {
		$route = new Jolt_Route_Named($route_name, 'Controller', 'action');
		
		$this->assertTrue($route->isValidUri($uri));
	}
	
	/**
	 * @dataProvider providerInvalidRouteAndUri
	 */
	public function test_Route_Will_Not_Allow_In_Valid_Uris($route_name, $uri) {
		$route = new Jolt_Route_Named($route_name, 'Controller', 'action');

		$this->assertFalse($route->isValidUri($uri));
	}
	
	public function test_Route_Is_Equal() {
		$route1 = $this->buildNamedRoute('/user/%d', 'User', 'viewAction');
		$route2 = $this->buildNamedRoute('/user/%d', 'User', 'viewAction');
		
		$this->assertTrue($route1->isEqual($route2));
	}
	
	public function test_Route_Is_Not_Equal() {
		$route1 = $this->buildNamedRoute('/user/%d', 'User', 'viewAction');
		$route2 = $this->buildNamedRoute('/user/%d', 'User', 'view');
		
		$this->assertFalse($route1->isEqual($route2));
	}
	
	/**
	 * @dataProvider providerValidRouteUriAndActionArguments
	 */
	public function test_Route_Argv_Is_Set($route_name, $uri, $argv) {
		$route = new Jolt_Route_Named($route_name, 'Controller', 'actionMethod');
		$route->isValidUri($uri);
		
		$this->assertEquals($argv, array_values($route->getArgv()));
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
			array('/abc/%n/'),
			array('/abc/def/efg/%n'),
			array('/abc/def/%s/%n'),
			array('/abc.def/%s/%n'),
			array('/abc/usr/%n/blah/%s'),
			array('/tutorial/%s.html'),
			array('/search/result-%n.html'),
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
			array('/abc/usr/%n/blah/%s', '/abc/usr/10/blah/hello'),
			array('/abc/usr/%n/blah/%s', '/abc/usr/1/blah/hello-world'),
			array('/abc/usr/%n/blah/%s', '/abc/usr/1/blah/hello world'),
			array('/tutorial/%s.html', '/tutorial/opengl-tutorial.html'),
			array('/tutorial/%s.html', '/tutorial/the-#named#-tutorial.html'),
			array('/tutorial/%s.html', "/tutorial/a tutorial about %s's.html"),
			array('/user/%n.html', '/user/10.html'),
			array('/user/%n.html', '/user/1.html'),
			array('/search/result-%n.html', '/search/result-10.html'),
			array('/search/result-%n.html', '/search/result-101345.html'),
			array('/add/balance/%n', '/add/balance/10.45')
		);
	}
	
	public function providerInvalidRouteAndUri() {
		return array(
			array('/abc', '/def'),
			array('/user/view', '/usr/view'),
			array('/user-long-route', '/user_long_route'),
			array('/abc/usr/%n/blah/%s', '/abc/usr/hello/blah/10'),
			array('/abc/usr/%n/blah/%s', '/abc/usr/hello-world/blah/10'),
			array('/abc/usr/%n/blah/%s', '/abc/usr/hello world/blah/1'),
			array('/tutorial/%s.html', '/tutorial/10.html'),
			array('/user/%n.html', '/user/user-vic.html'),
			array('/user/%s.html', '/user/1.html'),
			array('/search/result-%n.html', '/search/result-search-string.html'),
			array('/add/balance/%n', '/add/balance/some-amount')
		);
	}
	
	public function providerValidRouteUriAndActionArguments() {
		return array(
			array('/abc', '/abc', array()),
			array('/user/view', '/user/view', array()),
			array('/abc/usr/%n/blah/%s', '/abc/usr/10/blah/hello', array(10, 'hello')),
			array('/abc/usr/%n/blah/%s', '/abc/usr/1/blah/hello-world', array(1, 'hello-world')),
			array('/abc/usr/%n/blah/%s', '/abc/usr/1/blah/hello world', array(1, 'hello world')),
			array('/tutorial/%s.html', '/tutorial/opengl-tutorial.html', array('opengl-tutorial')),
			array('/tutorial/%s.html', '/tutorial/the-#named#-tutorial.html', array('the-#named#-tutorial')),
			array('/tutorial/%s.html', "/tutorial/a tutorial about %s's.html", array("a tutorial about %s's")),
			array('/user/%n.html', '/user/10.html', array(10)),
			array('/user/%n.html', '/user/1.html', array(1)),
			array('/search/result-%n.html', '/search/result-10.html', array(10)),
			array('/search/result-%n.html', '/search/result-101345.html', array(101345)),
			array('/add/balance/%n', '/add/balance/10.45', array(10.45))
		);
	}
}