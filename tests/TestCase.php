<?php

declare(encoding='UTF-8');
namespace Jolt;

class TestCase extends \PHPUnit_Framework_TestCase {
	public static function assertArray($a, $message = '') {
		self::assertThat(is_array($a), self::isTrue(), $message);
	}
	
	
	public static function assertEmptyArray($a, $message = '') {
		self::assertArray($a);
		self::assertEquals(count($a), 0, $message);
	}
	
	
	protected function buildMockAbstractRoute() {
		$mock = $this->getMockForAbstractClass('\Jolt\Route');
		return $mock;
	}
	
	
	protected function buildMockClient() {
		$mock = $this->getMock('\Jolt\Client');
		
		return $mock;
	}
	
	
	protected function buildMockDispatcher() {
		$mock = $this->getMock('\Jolt\Dispatcher');
		
		return $mock;
	}
	
	
	protected function buildMockEmptyRouter() {
		$mock = $this->getMock('\Jolt\Router', array('getRouteCount'));
		
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(0));
		
		return $mock;
	}
	
	
	protected function buildMockNamedRoute($route, $controller, $action) {
		$mock = $this->getMock('\Jolt\Route_Named',
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
	
	
	protected function buildMockRestfulRoute($route, $resource) {
		$mock = $this->getMock('\Jolt\Route_Restful',
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
	
	
	protected function buildMockRouter() {
		$named_route_list = array($this->buildMockNamedRoute('/abc', 'Controller', 'action'));
		
		$mock = $this->getMock('\Jolt\Router', array('getRouteCount'));
		
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(count($named_route_list)));
		
		return $mock;
	}
}