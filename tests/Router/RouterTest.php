<?php

require_once 'Jolt/Router.php';

class Jolt_Router_RouterTest extends Jolt_TestCase {

	public function test_Named_Route_List_Is_Initially_Empty() {
		$router = new Jolt_Router();
		
		$this->assertArray($router->getNamedRouteList());
		$this->assertEmptyArray($router->getNamedRouteList());
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function test_Named_Route_List_Can_Have_Valid_Routes($named_route) {
		$router = new Jolt_Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 * @dataProvider providerInvalidRouteList
	 */
	public function test_Named_Route_List_Cannot_Have_Invalid_Routes($named_route) {
		$router = new Jolt_Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Named_Route_List_Cannot_Have_Duplicates() {
		$router = new Jolt_Router();
		
		$route1 = $this->buildNamedRoute('/user', 'User', 'view');
		$route2 = $this->buildNamedRoute('/user', 'User', 'view');
		
		$router->setNamedRouteList(array($route1, $route2));
	}
	
	
	
	
	public function test_Restful_Route_List_Is_Initially_Empty() {
		$router = new Jolt_Router();
		
		$this->assertArray($router->getRestfulRouteList());
		$this->assertEmptyArray($router->getRestfulRouteList());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Restful_Route_List_Cannot_Have_Duplicates() {
		$router = new Jolt_Router();
		
		$route1 = $this->buildRestfulRoute('/user', 'User');
		$route2 = $this->buildRestfulRoute('/user', 'User');
		
		$router->setRestfulRouteList(array($route1, $route2));
	}
	
	
	public function test_Config_Can_Be_Set() {
		$router = new Jolt_Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function test_Config_Is_Set() {
		$router = new Jolt_Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($config, $router->setConfig($config)->getConfig());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Config_Can_Not_Be_Malformed() {
		$router = new Jolt_Router();
		$router->setConfig(array());
	}
	
	/**
	 * @expectedException Jolt_Exception
	 */
	public function test_Execute_Must_Have_At_Least_One_Route() {
		$router = new Jolt_Router();
		$router->execute();
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function test_Execute_Finds_Correct_Route($named_route) {
		$router = new Jolt_Router();
		$router->setConfig($this->buildConfig());

		$router->setNamedRouteList(array($named_route));
	}
	

	
	
	
	
	public function test_Uri_Can_Be_Set() {
		$router = new Jolt_Router();
		$this->assertEquals($router, $router->setUri('/user/1'));
	}
	
	public function test_Uri_Is_Set() {
		$router = new Jolt_Router();
		$this->assertEquals('/user/1', $router->setUri('/user/1')->getUri());
	}
	
	public function test_Uri_Must_Start_With_Slash() {
		$router = new Jolt_Router();
		$router->setUri('abc');
		$this->assertEquals('/abc', $router->getUri());
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