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
	 */
	public function testSetNamedRouteList() {
		$router = new Router();
		$router->setNamedRouteList('string');
		$router->setNamedRouteList(10);
		$router->setNamedRouteList(10.45);
		$router->setNamedRouteList(new \stdClass());
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetRestfulRouteList() {
		$router = new Router();
		$router->setRestfulRouteList('string');
		$router->setRestfulRouteList(10);
		$router->setRestfulRouteList(10.45);
		$router->setRestfulRouteList(new \stdClass());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNameListMustBeRouteObjects() {
		$router = new Router();
		
		$name_list = array(new \stdClass());
		$router->setNamedRouteList($name_list);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNameListCanNotBeEmpty() {
		$router = new Router();
		
		$name_list = array();
		$router->setNamedRouteList($name_list);
	}
	
	public function testConfigCanBeSet() {
		$router = new Router();
		$config = $this->buildConfig();
		
		$this->assertEquals($router, $router->setConfig($config));
	}
	
	public function testConfigIsSet() {
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
	
	

	protected function buildRouteObject() {

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