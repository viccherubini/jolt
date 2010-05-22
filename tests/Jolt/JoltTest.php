<?php

require_once 'Jolt/Jolt.php';

class Jolt_JoltTest extends Jolt_TestCase {
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testAttachedRouterMustHaveAtLeastOneRoute() {
		$router = $this->buildEmptyRouter();
		Jolt::attachRouter($router);
	}
	
	
	protected function buildEmptyRouter() {
		$mock = $this->getMock('Jolt_Router', array('getRouteCount'));
		
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(0));
		
		return $mock;
	}
	
	protected function buildRouter() {
		$named_route_list = array($this->buildNamedRoute('/abc', 'Controller', 'action'));
		
		$mock = $this->getMock('Jolt_Router', array('getRouteCount'));
		
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(count($named_route_list)));
		
		return $mock;
	}
}