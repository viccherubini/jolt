<?php

declare(encoding='UTF-8');
namespace jolt_test\route;

use \jolt\route\named,
	\jolt_test\testcase;

require_once('jolt/route/named.php');

class named_test extends testcase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRoute_MethodCanNotBeEmpty() {
		$route = new named(NULL, '/', 'Controller', 'Action');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRoute_RouteCanNotBeEmpty() {
		$route = new named('GET', NULL, 'Controller', 'Action');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRoute_ControllerCanNotBeEmpty() {
		$route = new named('GET', '/users/', NULL, 'Action');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRoute_ActionCanNotBeEmpty() {
		$route = new named('GET', '/users/', 'Controller', NULL);
	}

	public function testSetRequestMethod_MustBeGetPostPutDelete() {
		$route = new named('GET', '/', 'Controller', 'action');

		$route->setRequestMethod('GET');
		$this->assertEquals('GET', $route->getRequestMethod());

		$route->setRequestMethod('POST');
		$this->assertEquals('POST', $route->getRequestMethod());

		$route->setRequestMethod('PUT');
		$this->assertEquals('PUT', $route->getRequestMethod());

		$route->setRequestMethod('DELETE');
		$this->assertEquals('DELETE', $route->getRequestMethod());
	}

	public function testGetRoute_ReturnsRouteName() {
		$route = new named('GET', '/route/', 'Controller', 'Action');
		$this->assertEquals('/route/', $route->get_route());
	}

	public function testGetAction_ReturnsActionName() {
		$route = new named('GET', '/route/', 'Controller', 'Action');
		$this->assertEquals('Action', $route->getAction());
	}

	/**
	 * @dataProvider providerValidRoutePathAndActionArguments
	 */
	public function testGetArgv_ReturnsArgvAfterParsingPath($routeName, $path, $argv) {
		$route = new named('GET', $routeName, 'Controller', 'actionMethod');
		$route->is_valid_path($path);

		$this->assertEquals($argv, array_values($route->getArgv()));
	}

	public function testGetController_ReturnsControllerName() {
		$route = new named('GET', '/route/', 'Controller', 'Action');
		$this->assertEquals('Controller', $route->getController());
	}

	/**
	 * @expectedException \Jolt\Exception
	 * @dataProvider providerInvalidRequestMethod
	 */
	public function testGetMethod_MustBeGetPostPutDelete($requestMethod) {
		$route = new named($requestMethod, '/', 'Controller', 'action');
	}

	public function testIsEqual_ReturnsTrueForTwoIdenticalRoutes() {
		$route1 = $this->buildMockNamedRoute('GET', '/user/%d', 'User', 'viewAction');
		$route2 = $this->buildMockNamedRoute('GET', '/user/%d', 'User', 'viewAction');

		$this->assertTrue($route1->is_equal($route2));
		$this->assertTrue($route2->is_equal($route1));
	}

	public function testIsEqual_ReturnsFalseForTwoDifferentRoutes() {
		$route1 = $this->buildMockNamedRoute('GET', '/user/%d', 'User', 'viewAction');
		$route2 = $this->buildMockNamedRoute('GET', '/user/%d', 'User', 'view');

		$this->assertFalse($route1->is_equal($route2));
		$this->assertFalse($route2->is_equal($route1));
	}

	/**
	 * @dataProvider providerValidRoute
	 */
	public function testIsValid_ReturnsTrueForValidRoute($routeName) {
		$route = new named('GET', '/', 'Controller', 'Action');

		$this->assertTrue($route->set_route($routeName)->is_valid());
	}

	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function testIsValid_ReturnsFalseForInvalidRoute($routeName) {
		$route = new named('GET', '/', 'Controller', 'Action');

		$this->assertFalse($route->set_route($routeName)->is_valid());
	}

	/**
	 * @dataProvider providerValidRouteAndPath
	 */
	public function testIsValidPath_ReturnsTrueForValidRouteAndPath($routeName, $path) {
		$route = new named('GET', $routeName, 'Controller', 'action');

		$this->assertTrue($route->is_valid_path($path));
	}

	/**
	 * @dataProvider providerInvalidRouteAndPath
	 */
	public function testIsValidPath_ReturnsFalseForInvalidRouteAndPath($routeName, $path) {
		$route = new named('GET', $routeName, 'Controller', 'action');

		$this->assertFalse($route->is_valid_path($path));
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
			array('/abc.'),
			array('/abc/%n/def/%n')
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

	public function providerValidRouteAndPath() {
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
			array('/add/balance/%n', '/add/balance/10.45'),
			array('/abc/%n/def/%n', '/abc/10/def/20')
		);
	}

	public function providerInvalidRouteAndPath() {
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

	public function providerValidRoutePathAndActionArguments() {
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
			array('/add/balance/%n', '/add/balance/10.45', array(10.45)),
			array('/abc/%n/def/%n', '/abc/10/def/20', array(10, 20))
		);
	}

	public function providerInvalidRequestMethod() {
		return array(
			array(1),
			array(1.00),
			array('abc'),
			array(array('abc')),
			array(new \stdClass)
		);
	}
}