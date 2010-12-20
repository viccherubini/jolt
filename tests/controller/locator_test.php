<?php

declare(encoding='UTF-8');
namespace jolt_test\controller;

use \jolt\controller\locator,
	\jolt_test\testcase;

require_once('jolt/controller/locator.php');

class locator_test extends TestCase {

	private $action = NULL;
	private $namespace = 'jolt_app';
	private $controller = 'index';
	private $controller_file = 'index';

	public function setUp() {
		$this->action = "{$this->namespace}\\{$this->controller}";
	}

	public function testLoad_AppendsExt() {
		$locator = new locator;
		$locator->load(DIRECTORY_CONTROLLERS, $this->action);

		$this->assertEquals($this->controller_file . Locator::EXT, $locator->get_file());
	}

	public function testLoad_AppendsDirectorySeparator() {
		$locator = new locator;
		$locator->load(DIRECTORY_CONTROLLERS, $this->action);

		$this->assertEquals(DIRECTORY_CONTROLLERS . DIRECTORY_SEPARATOR, $locator->get_path());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerPathExists() {
		$locator = new locator;

		$locator->load('/path/to/non-existent/controllers', $this->action);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerExists() {
		$locator = new locator;

		$locator->load(DIRECTORY_CONTROLLERS, "{$this->namespace}\\broken");
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testLoad_ControllerInstanceOfJoltController() {
		$locator = new locator;

		$locator->load(DIRECTORY_CONTROLLERS, "{$this->namespace}\\broken2");
	}

	/**
	 * @dataProvider providerControllerAction
	 */
	public function testLoad_ReturnsController($action) {
		$locator = new locator;

		$controller = $locator->load(DIRECTORY_CONTROLLERS, $action);
		$this->assertTrue($controller instanceof \Jolt\Controller);
	}

	public function providerControllerAction() {
		return array(
			array('\\index2'),
			array('jolt_app\\index'),
			array('index3'),
			array('jolt_app\\user\\user'),
			array('user_controller')
		);
	}

}