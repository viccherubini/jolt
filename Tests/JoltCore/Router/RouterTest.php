<?php

namespace JoltTest;
use \Jolt\Router;

require_once 'Jolt/Router.php';

class JoltCore_Router_RouterTest extends TestCase {

	public function testEmptyNamedRouteList() {
		$router = new Router();
		
		$this->assertArray($router->getNamedRouteList());
		$this->assertEmptyArray($router->getNamedRouteList());
	}
	
	public function testEmptyRestfulRouteList() {
		$router = new Router();
		
		$this->assertArray($router->getRestfulRouteList());
		$this->assertEmptyArray($router->getRestfulRouteList());
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @provider providerNamedRoute
	 */
	public function testSetNamedRouteList($route_list) {
		$router = new Router();
		$router->setNamedRouteList($route_list);
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @provider providerInvalidRouteList
	 */
	public function testSetRestfulRouteList($route_list) {
		$router = new Router();
		$router->setRestfulRouteList($route_list);
	}
	
	public function testConfigCanBeSet() {
		$router = new Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function testSettingConfigReturnsSelf() {
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
	public function testDispatchMustHaveAtLeastOneRoute() {
		$router = new Router();
		$router->dispatch();
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