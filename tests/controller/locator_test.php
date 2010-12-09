<?php

declare(encoding='UTF-8');
namespace JoltTest\Controller;

use \Jolt\Controller\Locator,
	\JoltTest\TestCase;

require_once 'jolt/controller/locator.php';

class LocatorTest extends TestCase {

	private $action = NULL;
	private $namespace = 'JoltApp';
	private $controller = 'Index';
	private $controllerFile = 'index';
	
	public function setUp() {
		$this->action = "{$this->namespace}\\{$this->controller}";
	}

	public function testLoad_AppendsExt() {
		$locator = new Locator;
		$locator->load(DIRECTORY_CONTROLLERS, $this->action);
		
		$this->assertEquals($this->controllerFile . Locator::EXT, $locator->getFile());
	}

	public function testLoad_AppendsDirectorySeparator() {
		$locator = new Locator;
		$locator->load(DIRECTORY_CONTROLLERS, $this->action);
		
		$this->assertEquals(DIRECTORY_CONTROLLERS . DIRECTORY_SEPARATOR, $locator->getPath());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerPathExists() {
		$locator = new Locator;
		
		$locator->load('/path/to/non-existent/controllers', $this->action);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerExists() {
		$locator = new Locator;
		
		$locator->load(DIRECTORY_CONTROLLERS, "{$this->namespace}\\Broken");
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerInstanceOfJoltController() {
		$locator = new Locator;
		
		$locator->load(DIRECTORY_CONTROLLERS, "{$this->namespace}\\Broken2");
	}
	
	/**
	 * @dataProvider providerControllerAction
	 */
	public function testLoad_ReturnsController($action) {
		$locator = new Locator;
		
		$controller = $locator->load(DIRECTORY_CONTROLLERS, $action);
		$this->assertTrue($controller instanceof \Jolt\Controller);
	}
	
	public function providerControllerAction() {
		return array(
			array('\\Index2'),
			array('JoltApp\\Index'),
			array('Index3'),
			array('JoltApp\\User\\User'),
			array('UserController')
		);
	}
}
