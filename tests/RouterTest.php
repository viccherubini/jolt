<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Router;

require_once 'Jolt/Router.php';

class RouterTest extends TestCase {

	public function testRouteListIsInitiallyEmpty() {
		$router = new Router();
		
		$this->assertArray($router->getRouteList());
		$this->assertEmptyArray($router->getRouteList());
	}
	
	/**
	 * @dataProvider providerValidRouteList
	 */
	public function testRouteListMustHaveValidRoutes($route) {
		$router = new Router();
		$router->setRouteList(array($route));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 * @dataProvider providerInvalidRouteList
	 */
	public function testRouteListCannotHaveInvalidRoutes($route) {
		$router = new Router();
		$router->setRouteList(array($route));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteListCannotHaveDuplicateNamedRoutes() {
		$router = new Router();
		
		$route1 = $this->buildMockNamedRoute('/user', 'User', 'view');
		$route2 = $this->buildMockNamedRoute('/user', 'User', 'view');
		
		$router->setRouteList(array($route1, $route2));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteListCannotHaveDuplicateRestfulRoutes() {
		$router = new Router();
		
		$route1 = $this->buildMockRestfulRoute('/user', 'User');
		$route2 = $this->buildMockRestfulRoute('/user', 'User');
		
		$router->setRouteList(array($route1, $route2));
	}
	
	public function testConfigCanBeSetCorrectly() {
		$router = new Router();
		$config = $this->buildRouterConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function testConfigIsSetCorrectly() {
		$router = new Router();
		$config = $this->buildRouterConfig();
		
		$this->assertEquals($config, $router->setConfig($config)->getConfig());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testConfigCanNotBeMalformed() {
		$router = new Router();
		$router->setConfig(array());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecuteMustHaveAtLeastOneRoute() {
		$router = new Router();
		$router->execute($this->buildMockDispatcher());
	}
	
	/**
	 * @dataProvider providerValidRouteList
	 */
	public function testExecuteFindsCorrectRoute($route) {
		$router = new Router();
		$router->setConfig($this->buildRouterConfig());

		$router->setRouteList(array($route));
	}
	
	public function testUriCanBeSetCorrectly() {
		$router = new Router();
		$this->assertEquals($router, $router->setUri('/user/1'));
	}
	
	public function testUriIsSetCorrectly() {
		$router = new Router();
		$this->assertEquals('/user/1', $router->setUri('/user/1')->getUri());
	}
	
	public function testUriMustStartWithForwardSlash() {
		$router = new Router();
		$router->setUri('abc');
		$this->assertEquals('/abc', $router->getUri());
	}
	
	public function providerInvalidRouteList() {
		return array(
			array('string'),
			array(10),
			array(10.45),
			array(new \stdClass()),
			array(array())
		);
	}
	
	public function providerValidRouteList() {
		return array(
			array($this->buildMockNamedRoute('/', 'User', 'viewall')),
			array($this->buildMockNamedRoute('/user/', 'User', 'viewall')),
			array($this->buildMockNamedRoute('/user/%d', 'User', 'view')),
			array($this->buildMockNamedRoute('/user/delete/%d', 'User', 'delete')),
			array($this->buildMockRestfulRoute('/user', 'User')),
			array($this->buildMockRestfulRoute('/user_product', 'User_Product')),
			array($this->buildMockRestfulRoute('/order', 'Order'))
		);
	}
	
	protected function buildRouterConfig() {
		return array(
			'site_root' => 'http://joltcore.org/',
			'site_root_secure' => 'https://joltcore.org/',
			'app_dir' => 'app',
			'layout_dir' => '/public/layout',
			'default_layout' => 'index',
			'rewrite' => true
		);
	}
	
}