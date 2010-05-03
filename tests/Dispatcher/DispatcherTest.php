<?php

require_once 'vfsStream/vfsStream.php';

require_once 'Jolt/Dispatcher.php';

class Jolt_Dispatcher_DispatcherTest extends Jolt_TestCase {
	
	public function test_Dispatcher_Controller_Path_Is_Set() {
		$dispatcher = new Jolt_Dispatcher();
		
		$controller_path = getcwd();
		$dispatcher->setControllerPath($controller_path);
		
		$this->assertEquals($controller_path, $dispatcher->getControllerPath());
	}
	
	public function test_Dispatcher_Application_Path_Is_Set() {
		$dispatcher = new Jolt_Dispatcher();
		
		$application_path = getcwd();
		$dispatcher->setApplicationPath($application_path);
		
		$this->assertEquals($application_path, $dispatcher->getApplicationPath());
	}
	
	public function test_Dispatcher_Layout_Path_Is_Set() {
		$dispatcher = new Jolt_Dispatcher();
		
		$layout_path = getcwd();
		$dispatcher->setLayoutPath($layout_path);
		
		$this->assertEquals($layout_path, $dispatcher->getLayoutPath());
	}
	
	
	public function test_Dispatcher_Route_Is_Set() {
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
	
	
	
	
	
	//public function test_Dispatcher_Builds_Controller_Object() {
		//vfsStreamWrapper::register();
		//vfsStreamWrapper::setRoot(new vfsStreamDirectory('controllers'));
		
		
		
	//}
	
	
	
	
	
	protected function buildDispatcher() {
		$dispatcher = new Jolt_Dispatcher();
		$dispatcher->setApplicationPath(getcwd())
			->setControllerPath(getcwd())
			->setLayoutPath(getcwd());
		
		return $dispatcher;	
	}
}