<?php

require_once 'Jolt/Dispatcher.php';

class Jolt_Dispatcher_DispatcherTest extends Jolt_TestCase {
	
	public function test_Dispatcher_Controller_Path_Is_Set() {
		$dispatcher = new Jolt_Dispatcher();
		
		$controller_path = getcwd();
		$dispatcher->setControllerPath($controller_path);
		
		$this->assertEquals($controller_path, $dispatcher->getControllerPath());
	}
	
	
	public function test_Route_Is_Set() {
		$dispatcher = new Jolt_Dispatcher();
		
		$route = $this->buildNamedRoute('/user/%n', 'User', 'viewAction');
		$dispatcher->setRoute($route);
		
		$this->assertTrue($route->isEqual($dispatcher->getRoute()));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Dispatcher_Must_Have_Route_Before_Executing() {
		$dispatcher = new Jolt_Dispatcher();
		$dispatcher->dispatch();
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Dispatcher_Route_Must_Have_Controller_File_Set_Before_Executing() {
		$dispatcher = new Jolt_Dispatcher();
		
		$route = $this->buildAbstractRoute();
		
		$dispatcher->setRoute($route);
		$dispatcher->dispatch();
	}
}