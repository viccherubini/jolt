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
	public function testExecute_ControllerPathExists() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath('/path/to/controllers');
		
		$dispatcher->execute();
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_LocatorSet() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS);
		
		$dispatcher->execute();
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_RouteSet() {
		$locator = $this->buildMockControllerLocator();
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS)
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
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS)
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
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS)
			->attachLocator($locator)
			->attachRoute($route)
			->attachView($view);
		
		$dispatcher->execute();
	}
	
	public function testExecute_BuildsController() {
		$viewContent = $this->loadView('welcome');
		
		$controller = $this->buildController('Index');
		
		$locator = $this->getMock('\Jolt\Controller\Locator', array('load'));
		$locator->expects($this->once())
			->method('load')
			->will($this->returnValue($controller));
		
		$route = $this->buildMockNamedRoute('GET', '/', 'Index', 'viewAction');
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS)
			->attachLocator($locator)
			->attachRoute($route)
			->attachView($view);
		
		$this->assertTrue($dispatcher->execute());
		$this->assertEquals($viewContent, $dispatcher->getController()->getRenderedController());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testSetControllerPath_NotEmpty() {
		$dispatcher = new Dispatcher;
		
		$dispatcher->setControllerPath(NULL);
	}

	public function testSetControllerPath_IsSet() {
		$controllerPath = '/path/to/controllers/';
		
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath($controllerPath);
		
		$this->assertEquals($controllerPath, $dispatcher->getControllerPath());
	}
	
	public function testSetControllerPath_AppendsSeparator() {
		$dispatcher = new Dispatcher;
		$dispatcher->setControllerPath(DIRECTORY_CONTROLLERS);
		
		$this->assertEquals(DIRECTORY_CONTROLLERS . DIRECTORY_SEPARATOR, $dispatcher->getControllerPath());
	}
}