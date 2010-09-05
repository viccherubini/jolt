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
	
	public function testAddHeader_HeaderSet() {
		$controller = $this->buildMockController();
		$controller->addHeader('X-Powered-By', 'PHP/Jolt');
		
		$this->assertEquals('PHP/Jolt', $controller->getHeader('X-Powered-By'));
	}
	
	public function testAddHeader_ContentTypeSetToMemberVariable() {
		$controller = $this->buildMockController();
		$controller->addHeader('Content-Type', 'text/css');
		
		$this->assertEquals(0, count($controller->getHeaderList()));
		$this->assertEquals('text/css', $controller->getContentType());
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
	
	public function testExecute_UsesRendering() {
		$viewContent = $this->loadView('welcome');
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$controller = new Index;
		$controller->attachView($view);
		$controller->setAction('viewAction');
		
		$controller->execute(array());
		
		$this->assertEquals($viewContent, $controller->getRenderedController());
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ViewSet() {
		$controller = $this->buildMockController();
		$controller->render();
	}
	
	public function testRender_UsesActionForView() {
		$viewContent = $this->loadView('indexAction');
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->setAction('indexAction');
		$controller->render();
		
		$this->assertEquals($viewContent, $controller->getRenderedView());
	}
	
	public function testRenderToBlock_AddsBlock() {
		$viewContent = $this->loadView('indexAction');
		
		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->renderToBlock('indexAction', 'blockName');
		
		$this->assertEquals($viewContent, $controller->getBlock('blockName'));
	}
	
	public function testSetAction_IsTrimmed() {
		$action = ' indexAction ';
		
		$controller = $this->buildMockController();
		$controller->setAction($action);
		
		$this->assertEquals('indexAction', $controller->getAction());
	}
	
	public function testSetContentType_IsTrimmed() {
		$contentType = ' text/css ';
		
		$controller = $this->buildMockController();
		$controller->setContentType($contentType);
		
		$this->assertEquals('text/css', $controller->getContentType());
	}
	
	public function testSetResponseCode_IsInt() {
		$controller = $this->buildMockController();
		$controller->setResponseCode(404);
		
		$this->assertEquals(404, $controller->getResponseCode());
	}
	
	public function testSetResponseCode_IsCastToInt() {
		$controller = $this->buildMockController();
		$controller->setResponseCode('string');
		
		$this->assertEquals(0, $controller->getResponseCode());
	}
	
	public function testGetBlockList_FullList() {
		$view = $this->buildMockViewObject();
		
		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->addBlock('abc', '<strong>def</strong>');
		
		$blockList = $controller->getBlockList();
		$this->assertGreaterThan(0, count($blockList));
	}
	
	public function testGetBlockList_EmptyList() {
		$controller = $this->buildMockController();
		$controller->addBlock('abc', '<strong>def</strong>');
		
		$blockList = $controller->getBlockList();
		$this->assertEmptyArray($blockList);
	}
	
	public function testGetBlock_EmptyBlock() {
		$controller = $this->buildMockController();
		
		$this->assertTrue(is_null($controller->getBlock('missing-block')));
	}
	
	public function testGetContentType_IsOriginallyTextHtml() {
		$controller = $this->buildMockController();
		
		$this->assertEquals('text/html', $controller->getContentType());
	}
	
	public function testGetHeaderList_FullList() {
		$controller = $this->buildMockController();
		$controller->addHeader('X-Powered-By', 'PHP/Jolt');
		
		$headerList = $controller->getHeaderList();
		$this->assertGreaterThan(0, count($headerList));
	}
	
	public function testGetHeader_EmptyHeader() {
		$controller = $this->buildMockController();
		
		$this->assertTrue(is_null($controller->getHeader('Content-Type')));
	}
	
	public function testGetResponseCode_200ByDefault() {
		$controller = $this->buildMockController();
		$this->assertEquals(200, $controller->getResponseCode());
	}
}