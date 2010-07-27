<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Router;

require_once 'Jolt/Router.php';

class RouterTest extends TestCase {

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
	
	public function testAddRoute_ReturnsRouterObject() {
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
	public function testExecute_MustHaveAtLeastOneRoute() {
		$router = new Router;
		$router->execute();
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_MustFindPath() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		
		$router = new Router;
		$router->addRoute($namedRoute);
		
		$router->execute();
	}
	
	
	
	public function testExecute_FindsMatchedRoute() {
		
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetParameters_MustBeArray() {
		$router = new Router;
		$router->setParameters('11');
	}
	
	public function testSetParameters_ReturnsRouterObject() {
		$router = new Router;
		$this->assertTrue($router->setParameters(array()) instanceof \Jolt\Router);
	}
	
	public function testSetRequestMethod_CapitalizesMethod() {
		$requestMethod = 'get';
		
		$router = new Router;
		$router->setRequestMethod($requestMethod);
		
		$this->assertEquals(strtoupper($requestMethod), $router->getRequestMethod());
	}
	
	private function buildRouterWithRouteParameter() {
		$router = new Router;
		
		
		
		
	}
}