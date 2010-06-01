<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Route.php';

class RouteTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = $this->buildMockAbstractRoute();
		$route->setRoute(NULL);
	}
	
	
	public function testControllerFileIsSet() {
		$route = $this->buildMockAbstractRoute();
		
		$controller_file = 'ControllerFile.php';
		$route->setControllerFile($controller_file);
		
		$this->assertEquals($controller_file, $route->getControllerFile());
	}

}