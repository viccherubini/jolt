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
	
	
	public function testExecute_FindsMatchedRoute() {
		
	}
}