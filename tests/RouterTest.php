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
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetInputVariables_MustBeArray() {
		$router = new Router;
		$router->setInputVariables('11');
	}
	
	public function testSetInputVariables_ReturnsRouterObject() {
		$router = new Router;
		$this->assertTrue($router->setInputVariables(array()) instanceof \Jolt\Router);
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
	
	public function testAddRoute_ReturnsRouterObject() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		$restfulRoute = $this->buildMockRestfulRoute('/user', 'User');
		
		$router = new Router;
		$this->assertTrue($router->addRoute($namedRoute) instanceof \Jolt\Router);
		$this->assertTrue($router->addRoute($restfulRoute) instanceof \Jolt\Router);
		$this->assertEquals(2, count($router->getRouteList()));
	}
	
	public function testExecute_FindsMatchedRoute() {
		
	}
}