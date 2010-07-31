<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Dispatcher;

require_once 'Jolt/Dispatcher.php';

class DispatcherTest extends TestCase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ControllerDirectoryMustExist() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory('/path/to/controllers');
		
		$dispatcher->execute();
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_RouteMustBeSet() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory(DIRECTORY_CONTROLLERS);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ControllerFileMustExist() {
		$route = $this->buildMockNamedRoute('GET', '/', 'NotFound', 'index');
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory(DIRECTORY_CONTROLLERS)
			->setRoute($route);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testSetControllerDirectory_CanNotBeEmpty() {
		$dispatcher = new Dispatcher;
		
		$dispatcher->setControllerDirectory(NULL);
	}

	public function testSetControllerDirectory_IsSetProperly() {
		$controllerDirectory = '/path/to/controllers/';
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory($controllerDirectory);
		
		$this->assertEquals($controllerDirectory, $dispatcher->getControllerDirectory());
	}
	
	public function testSetControllerDirectory_AppendsDirectorySeparatorIfNeeded() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory(DIRECTORY_CONTROLLERS);
		
		$this->assertEquals(DIRECTORY_CONTROLLERS . DIRECTORY_SEPARATOR, $dispatcher->getControllerDirectory());
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltRoute
	 */
	public function testSetRoute_MustBeJoltRouteObject($route) {
		$dispatcher = new Dispatcher;
		
		$dispatcher->setRoute($route);
	}
	
	public function testSetRoute_CanSetJoltRoute() {
		$dispatcher = new Dispatcher;
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		
		$this->assertTrue($dispatcher->setRoute($route) instanceof \Jolt\Dispatcher);
	}
	
	
	public function providerInvalidJoltRoute() {
		return array(
			array('a'),
			array(10),
			array(array('a')),
			array(new \stdClass)
		);
	}
}