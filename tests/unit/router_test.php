<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\router,
	\jolt_test\testcase;

require_once('jolt/router.php');

class router_test extends testcase {

	private $http404Route = NULL;

	public function setUp() {
		$this->http404Route = $this->buildMockNamedRoute('GET', '/', 'NotFound', 'index');
	}

	public function testNewRouter_RouteListIsInitiallyEmpty() {
		$router = new Router;

		$this->assertArray($router->getRouteList());
		$this->assertEmptyArray($router->getRouteList());
	}

	public function testNewRouter_RequestMethodIsGetByDefault() {
		$router = new Router;
		$this->assertEquals('GET', $router->getRequestMethod());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAddRoute_MustBeJoltRoute() {
		$router = new Router;
		$router->addRoute('11');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAddRoute_CanOnlyAddSameNamedRouteOnce() {
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');

		$router = new Router;
		$router->addRoute($route);
		$router->addRoute($route);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAddRoute_CanOnlyAddSameRestfulRouteOnce() {
		$route = $this->buildMockRestfulRoute('/user', 'User');

		$router = new Router;
		$router->addRoute($route);
		$router->addRoute($route);
	}

	public function testAddRoute_AllowsSameRoutesOfDifferentRequestMethods() {
		$route1 = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		$route2 = $this->buildMockNamedRoute('POST', '/', 'Index', 'index');

		$router = new Router;
		$router->addRoute($route1);
		$router->addRoute($route2);

		$this->assertEquals(2, count($router->getRouteList()));
	}

	public function testAddRoute_ReturnsJoltRouterObject() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		$restfulRoute = $this->buildMockRestfulRoute('/user', 'User');

		$router = new Router;
		$this->assertTrue($router->addRoute($namedRoute) instanceof \Jolt\Router);
		$this->assertTrue($router->addRoute($restfulRoute) instanceof \Jolt\Router);
		$this->assertEquals(2, count($router->getRouteList()));
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAddRouteList_MustBeNonEmptyList() {
		$router = new Router;
		$router->addRouteList(array());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAddRouteList_RoutesMustBeUnique() {
		$routeList = array();
		$routeList[] = $this->buildMockNamedRoute('GET', '/', 'Controller', 'indexAction');
		$routeList[] = $this->buildMockNamedRoute('GET', '/', 'Controller', 'indexAction');

		$router = new Router;
		$router->addRouteList($routeList);
	}

	public function testAddRouteList_IsChainable() {
		$routeList = array();
		$routeList[] = $this->buildMockNamedRoute('GET', '/', 'Controller', 'indexAction');
		$routeList[] = $this->buildMockNamedRoute('POST', '/', 'Controller', 'indexAction');

		$router = new Router;

		$this->assertTrue($router->addRouteList($routeList) instanceof \Jolt\Router);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_MustHaveAtLeastOneRoute() {
		$router = new Router;
		$router->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function _testExecute_MustFindPath() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');

		$router = new Router;
		$router->addRoute($namedRoute)
			->setHttp404Route($this->http404Route);

		$router->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_MustHave404Route() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		$parameters = $this->buildMockParameters('/abc');

		$router = new Router;
		$router->setParameters($parameters);
		$router->addRoute($namedRoute);

		$router->execute();
	}

	public function testExecute_MustReturnJoltRouteObject() {
		$namedRoute = $this->buildMockNamedRoute('DELETE', '/user', 'User', 'index');
		$parameters = $this->buildMockParameters('/user');

		$router = new Router;
		$router->setRequestMethod('DELETE')
			->setHttp404Route($this->http404Route)
			->setParameters($parameters);
		$router->addRoute($namedRoute);

		$matchedRoute = $router->execute();

		$this->assertTrue($matchedRoute instanceof \Jolt\Route);
		$this->assertTrue($matchedRoute->is_equal($namedRoute));
		$this->assertTrue($namedRoute->is_equal($matchedRoute));
	}

	public function testExecute_UsesHttp404RouteWhenNoPathFound() {
		$namedRoute = $this->buildMockNamedRoute('PUT', '/user', 'User', 'index');
		$parameters = $this->buildMockParameters('/abc');

		$router = new Router;
		$router->setRequestMethod('PUT')
			->setHttp404Route($this->http404Route)
			->setParameters($parameters);
		$router->addRoute($namedRoute);

		$matchedRoute = $router->execute();

		$this->assertTrue($matchedRoute->is_equal($this->http404Route));
		$this->assertTrue($this->http404Route->is_equal($matchedRoute));
	}

	public function testExecute_RouteMustHaveSameRequestMethod() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		$parameters = $this->buildMockParameters('/user');

		$router = new Router;
		$router->setRequestMethod('POST')
			->setHttp404Route($this->http404Route)
			->setParameters($parameters);
		$router->addRoute($namedRoute);

		$matchedRoute = $router->execute();

		$this->assertFalse($matchedRoute->is_equal($namedRoute));
		$this->assertFalse($namedRoute->is_equal($matchedRoute));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetParameters_MustBeArray() {
		$router = new Router;
		$router->setParameters('11');
	}

	public function testSetParameters_ReturnsJoltRouterObject() {
		$router = new Router;
		$this->assertTrue($router->setParameters(array()) instanceof \Jolt\Router);
	}

	public function testSetRequestMethod_CapitalizesMethod() {
		$requestMethod = 'get';

		$router = new Router;
		$router->setRequestMethod($requestMethod);

		$this->assertEquals(strtoupper($requestMethod), $router->getRequestMethod());
	}

	public function testSetRouteParameter_IsSet() {
		$routeParameter = '__j';

		$router = new Router;
		$router->setRouteParameter($routeParameter);

		$this->assertEquals($routeParameter, $router->getRouteParameter());
	}

	public function testGetPath_PathIsTrimmed() {
		$path = ' /path/to/Result/10	';
		$pathTrimmed = '/path/to/Result/10';

		$router = new Router;
		$router->setPath($path);

		$this->assertEquals($pathTrimmed, $router->getPath());
	}

	private function buildMockParameters($path) {
		$router = new Router;
		$routeParameter = $router->getRouteParameter();

		return array($routeParameter => $path);
	}
}
