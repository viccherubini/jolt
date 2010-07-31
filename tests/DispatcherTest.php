<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Dispatcher;

require_once 'Jolt/Dispatcher.php';

class DispatcherTest extends TestCase {
	
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachRoute_MustBeJoltRouteObject($route) {
		$dispatcher = new Dispatcher;
		
		$dispatcher->attachRoute($route);
	}
	
	public function testAttachRoute_CanSetJoltRoute() {
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		$dispatcher = new Dispatcher;
		
		$this->assertTrue($dispatcher->attachRoute($route) instanceof \Jolt\Dispatcher);
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachView_MustBeJoltViewObject($view) {
		$dispatcher = new Dispatcher;
		
		$dispatcher->attachView($view);
	}
	
	public function testAttachView_CanSetJoltView() {
		$view = $this->buildMockView();
		$dispatcher = new Dispatcher;
		
		$this->assertTrue($dispatcher->attachView($view) instanceof \Jolt\Dispatcher);
	}
	
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
	public function testExecute_ViewMustBeSet() {
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory(DIRECTORY_CONTROLLERS)
			->attachRoute($route);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ControllerFileMustExist() {
		$route = $this->buildMockNamedRoute('GET', '/', 'NotFound', 'index');
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerDirectory(DIRECTORY_CONTROLLERS)
			->attachRoute($route);
		
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
	
	
	public function providerInvalidJoltObject() {
		return array(
			array('a'),
			array(10),
			array(array('a')),
			array(new \stdClass)
		);
	}
}