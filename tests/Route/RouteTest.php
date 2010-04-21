<?php

require_once 'Jolt/Route.php';

class Jolt_Route_RouteTest extends Jolt_TestCase {
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Route_Can_Not_Be_Empty() {
		$route = $this->buildAbstractRoute();
		$route->setRoute(NULL);
	}
	
	
	public function test_Controller_File_Is_Set() {
		$route = $this->buildAbstractRoute();
		
		$controller_file = 'ControllerFile.php';
		$route->setControllerFile($controller_file);
		
		$this->assertEquals($controller_file, $route->getControllerFile());
	}

}