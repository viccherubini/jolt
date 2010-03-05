<?php

require_once 'JoltCore/TestCase.php';

/**
 * @see Jolt_Router
 */
require_once 'Jolt/Router.php';

class JoltCore_Router_RouterTest extends JoltCore_TestCase {

	public function testEmptyNamedRouteList() {
		$router = new Router();
		
		$this->assertArray($router->getNamedRouteList());
		$this->assertEmptyArray($router->getNamedRouteList());
	}
	
	public function testEmptyRestfulRouteList() {
		$router = new Router();
		
		$this->assertArray($router->getRestfulRouteList());
		$this->assertEmptyArray($router->getRestfulRouteList());
	}
	
	
}