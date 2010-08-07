<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Controller,
	\JoltTest\TestCase,
	\JoltApp\Index;

require_once 'Jolt/Controller.php';
require_once DIRECTORY_CONTROLLERS . DS . 'Index.php';

class ControllerTest extends TestCase {
	
	public function test__Set_ViewSet() {
		$controller = $this->buildMockController();
		$controller->name = 'Vic';
		
		$this->assertTrue(is_null($controller->name));
	}
	
	public function test__Set_SetsValueToView() {
		$name = 'Vic';
		$view = $this->buildMockViewObject();
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->name = $name;
		
		$this->assertEquals($name, $controller->name);
	}
	
	public function testAddBlock_InsertsHtml() {
		$block = '<script type="text/javascript" src="jquery.js"></script>';
		
		$controller = $this->buildMockController();
		$controller->addBlock('scripts', $block);
		
		$this->assertEquals($block, $controller->getBlock('scripts'));
	}
	
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachView_IsView($view) {
		$controller = $this->buildMockController();
		
		$controller->attachView($view);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ActionSet() {
		$controller = $this->buildMockController();
		$controller->execute(array());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ActionExists() {
		$controller = $this->buildMockController();
		$controller->setAction('missingAction');
		
		$controller->execute(10); // Ensure this gets converted to an array
	}

	public function testExecute_ParamCount() {
		$action = 'paramAction';
		
		$controller = new Index;
		$controller->setAction($action);
		
		$reflectionAction = new \ReflectionMethod($controller, $action);
		$expectedParamCount = $reflectionAction->getNumberOfRequiredParameters();
		
		$actualParamCount = $controller->execute(array(10));
		
		$this->assertEquals($expectedParamCount, $actualParamCount);
	}
	
	public function testExecute_StaticAction() {
		$action = 'staticAction';
		$expectedContent = 'static content';
		
		$controller = new Index;
		$controller->setAction($action);
		
		$actualContent = $controller->execute(array());
		
		$this->assertEquals($expectedContent, $actualContent);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ViewSet() {
		$controller = $this->buildMockController();
		$controller->render();
	}
	
	public function testRender_UsesActionForView() {
		$viewName = 'indexAction';
		$viewFile = DIRECTORY_VIEWS . DS . $viewName . Controller::EXT;
		$viewContent = ( is_file($viewFile) ? file_get_contents($viewFile) : NULL );
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->setAction('indexAction');
		$controller->render();
		
		$this->assertEquals($viewContent, $controller->getRenderedContent());
	}
	
	public function testRender_AddsBlock() {
		$viewName = 'indexAction';
		$viewFile = DIRECTORY_VIEWS . DS . $viewName . Controller::EXT;
		$viewContent = ( is_file($viewFile) ? file_get_contents($viewFile) : NULL );
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->setAction('indexAction');
		$controller->render(NULL, 'blockName');
		
		$this->assertEquals($viewContent, $controller->getBlock('blockName'));
	}
	
	public function testSetAction_IsTrimmed() {
		$action = ' indexAction ';
		
		$controller = $this->buildMockController();
		$controller->setAction($action);
		
		$this->assertEquals('indexAction', $controller->getAction());
	}
	
	public function testGetBlockList_FullList() {
		$controller = $this->buildMockController();
		$controller->addBlock('abc', '<strong>def</strong>');
		
		$blockList = $controller->getBlockList();
		$this->assertGreaterThan(0, count($blockList));
	}
	
	public function testGetBlock_EmptyBlock() {
		$controller = $this->buildMockController();
		
		$this->assertTrue(is_null($controller->getBlock('missing-block')));
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