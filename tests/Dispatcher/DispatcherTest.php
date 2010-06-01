<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Dispatcher.php';

class DispatcherTest extends TestCase {
	
	public function testControllerPathIsSetCorrectly() {
		$dispatcher = new Dispatcher();
		
		$controller_path = getcwd();
		$dispatcher->setControllerPath($controller_path);
		
		$this->assertEquals($controller_path, $dispatcher->getControllerPath());
	}
	
	public function testApplicationPathIsSetCorrectly() {
		$dispatcher = new Dispatcher();
		
		$application_path = getcwd();
		$dispatcher->setApplicationPath($application_path);
		
		$this->assertEquals($application_path, $dispatcher->getApplicationPath());
	}
	
	public function testLayoutPathIsSetCorrectly() {
		$dispatcher = new Dispatcher();
		
		$layout_path = getcwd();
		$dispatcher->setLayoutPath($layout_path);
		
		$this->assertEquals($layout_path, $dispatcher->getLayoutPath());
	}
	
	
	public function testRouteIsSetCorrectly() {
		$dispatcher = new Dispatcher();
		
		$route = $this->buildMockNamedRoute('/user/%n', 'User', 'viewAction');
		$dispatcher->setRoute($route);
		
		$this->assertTrue($route->isEqual($dispatcher->getRoute()));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testDispatcherMustHaveRouteBeforeExecuting() {
		$dispatcher = new Dispatcher();
		
		$dispatcher->dispatch($this->buildMockClient());
	}
	
}