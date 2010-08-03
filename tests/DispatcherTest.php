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
	public function testAttachRoute_IsRoute($route) {
		$dispatcher = new Dispatcher;
		
		$dispatcher->attachRoute($route);
	}
	
	public function testAttachRoute_SetRoute() {
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		$dispatcher = new Dispatcher;
		
		$this->assertTrue($dispatcher->attachRoute($route) instanceof \Jolt\Dispatcher);
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachView_IsView($view) {
		$dispatcher = new Dispatcher;
		
		$dispatcher->attachView($view);
	}
	
	public function testAttachView_ViewSet() {
		$view = $this->buildMockView();
		$dispatcher = new Dispatcher;
		
		$this->assertTrue($dispatcher->attachView($view) instanceof \Jolt\Dispatcher);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_DirExists() {
		$dispatcher = new Dispatcher;
		$dispatcher->setDir('/path/to/controllers');
		
		$dispatcher->execute();
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_LocatorSet() {
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_RouteSet() {
		$locator = $this->buildMockControllerLocator();
		
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS)
			->attachLocator($locator);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ViewSet() {
		$locator = $this->buildMockControllerLocator();
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'index');
		
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS)
			->attachLocator($locator)
			->attachRoute($route);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ControllerExists() {
		// This is built here rather than using buildMockControllerLocator() so it'll throw an exception
		$locator = $this->getMock('\Jolt\Controller\Locator', array('load'));
		$locator->expects($this->once())
			->method('load')
			->will($this->throwException(new \Jolt\Exception));
		
		$route = $this->buildMockNamedRoute('GET', '/', 'NotFound', 'index');
		$view = $this->buildMockView();
		
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS)
			->attachLocator($locator)
			->attachRoute($route)
			->attachView($view);
		
		$dispatcher->execute();
	}
	
	public function testExecute_BuildsController() {
		$locator = $this->buildMockControllerLocator();
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'indexAction');
		$view = $this->buildMockView();
		
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS)
			->attachLocator($locator)
			->attachRoute($route)
			->attachView($view);
		
		$this->assertTrue($dispatcher->execute());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testSetDir_NotEmpty() {
		$dispatcher = new Dispatcher;
		
		$dispatcher->setDir(NULL);
	}

	public function testSetDir_IsSet() {
		$controllerDirectory = '/path/to/controllers/';
		
		$dispatcher = new Dispatcher;
		$dispatcher->setDir($controllerDirectory);
		
		$this->assertEquals($controllerDirectory, $dispatcher->getDir());
	}
	
	public function testSetDir_AppendsSeparator() {
		$dispatcher = new Dispatcher;
		$dispatcher->setDir(DIRECTORY_CONTROLLERS);
		
		$this->assertEquals(DIRECTORY_CONTROLLERS . DIRECTORY_SEPARATOR, $dispatcher->getDir());
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