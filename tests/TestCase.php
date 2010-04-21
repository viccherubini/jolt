<?php

class Jolt_TestCase extends PHPUnit_Framework_TestCase {
	
	public static function assertArray($a, $message = '') {
		self::assertThat(is_array($a), self::isTrue(), $message);
	}
	
	public static function assertEmptyArray($a, $message = '') {
		self::assertArray($a);
		self::assertEquals(count($a), 0, $message);
	}
	
	protected function buildAbstractRoute() {
		$mock = $this->getMockForAbstractClass('Jolt_Route');
		return $mock;
	}
	
	protected function buildNamedRoute($route, $controller, $action) {
		$mock = $this->getMock('Jolt_Route_Named',
			array('getRoute', 'getController', 'getAction'),
			array($route, $controller, $action)
		);
		
		$mock->expects($this->any())
			->method('getRoute')
			->will($this->returnValue($route));

		$mock->expects($this->any())
			->method('getController')
			->will($this->returnValue($controller));
			
		$mock->expects($this->any())
			->method('getAction')
			->will($this->returnValue($action));
			
		return $mock;
	}
	
	protected function buildRestfulRoute($route, $resource) {
		$mock = $this->getMock('Jolt_Route_Restful',
			array('getRoute', 'getResource'),
			array($route, $resource)
		);
		
		$mock->expects($this->any())
			->method('getRoute')
			->will($this->returnValue($route));
		
		$mock->expects($this->any())
			->method('getResource')
			->will($this->returnValue($resource));
			
		return $mock;
	}
}