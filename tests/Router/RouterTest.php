<?php

require_once 'Jolt/Router.php';

class Jolt_Router_RouterTest extends Jolt_TestCase {

	public function testEmptyNamedRouteList() {
		$router = new Jolt_Router();
		
		$this->assertArray($router->getNamedRouteList());
		$this->assertEmptyArray($router->getNamedRouteList());
	}
	
	public function testEmptyRestfulRouteList() {
		$router = new Jolt_Router();
		
		$this->assertArray($router->getRestfulRouteList());
		$this->assertEmptyArray($router->getRestfulRouteList());
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function testSettingValidNamedRouteList($named_route) {
		$router = new Jolt_Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 * @dataProvider providerInvalidRouteList
	 */
	public function testSettingInvalidNamedRouteList($named_route) {
		$router = new Jolt_Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	public function testConfigCanBeSet() {
		$router = new Jolt_Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function testSettingConfigReturnsSelf() {
		$router = new Jolt_Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($config, $router->setConfig($config)->getConfig());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testConfigCanNotBeMalformed() {
		$router = new Jolt_Router();
		$router->setConfig(array());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testDispatchMustHaveAtLeastOneRoute() {
		$router = new Jolt_Router();
		$router->dispatch();
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function testDispatchFindsCorrectRoute($named_route) {
		$router = new Jolt_Router();
		$router->setConfig($this->buildConfig());

		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouterCanNotHaveDuplicateNamedRoutes() {
		$router = new Jolt_Router();
		
		$route1 = $this->buildNamedRoute('/user', 'User', 'view');
		$route2 = $this->buildNamedRoute('/user', 'User', 'view');
		
		$router->setNamedRouteList(array($route1, $route2));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function testRouterCanNotHaveDuplicateRestfulRoutes() {
		$router = new Jolt_Router();
		
		$route1 = $this->buildRestfulRoute('/user', 'User');
		$route2 = $this->buildRestfulRoute('/user', 'User');
		
		$router->setRestfulRouteList(array($route1, $route2));
	}
	
	public function providerInvalidRouteList() {
		return array(
			array('string'),
			array(10),
			array(10.45),
			array(new stdClass()),
			array(array())
		);
	}
	
	public function providerValidNamedRouteList() {
		return array(
			array($this->buildNamedRoute('/', 'User', 'viewall')),
			array($this->buildNamedRoute('/user/', 'User', 'viewall')),
			array($this->buildNamedRoute('/user/%d', 'User', 'view')),
			array($this->buildNamedRoute('/user/delete/%d', 'User', 'delete'))
		);
	}
	
	public function providerValidRestfulRouteList() {
		return array(
			array($this->buildRestfulRoute('/user', 'User')),
			array($this->buildRestfulRoute('/user_product', 'User_Product')),
			array($this->buildRestfulRoute('/order', 'Order'))
		);
	}
	
	protected function buildConfig() {
		return array(
			'site_root' => 'http://joltcore.org/',
			'site_root_secure' => 'https://joltcore.org/',
			'app_dir' => 'app',
			'layout_dir' => '/public/layout',
			'default_layout' => 'index',
			'rewrite' => true
		);
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