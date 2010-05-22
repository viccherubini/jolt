<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Router.php';

class RouterTest extends TestCase {

	public function testNamedRouteListIsInitiallyEmpty() {
		$router = new Router();
		
		$this->assertArray($router->getNamedRouteList());
		$this->assertEmptyArray($router->getNamedRouteList());
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function testNamedRouteListCanHaveValidRoutes($named_route) {
		$router = new Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 * @dataProvider providerInvalidRouteList
	 */
	public function testNamedRouteListCannotHaveInvalidRoutes($named_route) {
		$router = new Router();
		$router->setNamedRouteList(array($named_route));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNamedRouteListCannotHaveDuplicates() {
		$router = new Router();
		
		$route1 = $this->buildNamedRoute('/user', 'User', 'view');
		$route2 = $this->buildNamedRoute('/user', 'User', 'view');
		
		$router->setNamedRouteList(array($route1, $route2));
	}
	
	public function testRestfulRouteListIsInitiallyEmpty() {
		$router = new Router();
		
		$this->assertArray($router->getRestfulRouteList());
		$this->assertEmptyArray($router->getRestfulRouteList());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function test_RestfulRouteListCannotHaveDuplicates() {
		$router = new Router();
		
		$route1 = $this->buildRestfulRoute('/user', 'User');
		$route2 = $this->buildRestfulRoute('/user', 'User');
		
		$router->setRestfulRouteList(array($route1, $route2));
	}
	
	public function testConfigCanBeSetCorrectly() {
		$router = new Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function testConfigIsSetCorrectly() {
		$router = new Router();
		$config = $this->buildConfig();
		
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
		$router->execute();
	}
	
	/**
	 * @dataProvider providerValidNamedRouteList
	 */
	public function testExecuteFindsCorrectRoute($named_route) {
		$router = new Router();
		$router->setConfig($this->buildConfig());

		$router->setNamedRouteList(array($named_route));
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
}