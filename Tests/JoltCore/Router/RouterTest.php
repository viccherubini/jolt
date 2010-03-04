<?php

require_once 'PHPUnit/Framework.php';
require_once 'Jolt/Router.php';

class JoltCore_Router_RouterTest extends PHPUnit_Framework_TestCase {

	public function testEmptyNamedRouteList() {
		$router = new Router();
		
		$this->assertEquals(is_array(array()), is_array($router->getNamedRouteList()));
		$this->assertEquals(0, count($router->getNamedRouteList()));
	}
	
	public function testEmptyRestfulRouteList() {
		$router = new Router();
		
		$this->assertEquals(is_array(array()), is_array($router->getRestfulRouteList()));
		$this->assertEquals(0, count($router->getRestfulRouteList()));
	}
	
	
}