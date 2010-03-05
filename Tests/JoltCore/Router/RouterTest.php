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
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetNamedRouteList() {
		$router = new Router();
		$router->setNamedRouteList('string');
		$router->setNamedRouteList(10);
		$router->setNamedRouteList(10.45);
		$router->setNamedRouteList(new stdClass());
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetRestfulRouteList() {
		$router = new Router();
		$router->setRestfulRouteList('string');
		$router->setRestfulRouteList(10);
		$router->setRestfulRouteList(10.45);
		$router->setRestfulRouteList(new stdClass());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testNameListMustBeRouteObjects() {
		$router = new Router();
		
		$name_list = array(new stdClass());
		$router->setNamedRouteList($name_list);
	}


	
	protected function buildRouteObject() {
		
		
	}
}