<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Controller,
	\Jolt\View,
	\JoltTest\TestCase,
	\JoltApp\Index;

require_once 'jolt/controller.php';
require_once 'jolt/view.php';

require_once DIRECTORY_CONTROLLERS . DS . 'index.php';

class ControllerTest extends TestCase {

	public function test__Set_RequiresView() {
		$controller = $this->buildMockController();
		$controller->name = 'Vic';

		$this->assertTrue(is_null($controller->name));
	}

	public function test__Set_SetsValueToView() {
		$name = 'name';
		$view = $this->getMock('\Jolt\View', array('__get'));
		$view->expects($this->once())->method('__get')->will($this->returnArgument(0));

		$controller = new Controller;
		$controller->attachView($view);
		$controller->name = $name;

		$this->assertEquals($name, $controller->name);
	}

	public function testAddHeader_HeaderSet() {
		$controller = new Controller;
		$controller->addHeader('X-Powered-By', 'PHP/Jolt');

		$this->assertEquals('PHP/Jolt', $controller->getHeader('X-Powered-By'));
	}

	public function testAddHeader_ContentTypeSetToMemberVariable() {
		$controller = new Controller;
		$controller->addHeader('Content-Type', 'text/css');

		$this->assertEquals(0, count($controller->getHeaderList()));
		$this->assertEquals('text/css', $controller->getContentType());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachView_IsView($view) {
		$controller = new Controller;

		$controller->attachView($view);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ActionSet() {
		$controller = new Controller;
		$controller->execute(array());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testExecute_ActionExists() {
		$controller = new Controller;
		$controller->setAction('missingAction');

		$controller->execute(10);
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

	public function _testExecute_UsesRendering() {
		// BROKEN TEST
		$viewContent = $this->loadView('welcome');

		$view = $this->getMock('\Jolt\View');
		$view->setViewPath(DIRECTORY_VIEWS);

		$controller = new Index;
		$controller->attachView($view);
		$controller->setAction('viewAction');

		$controller->execute(array());

		$this->assertEquals($viewContent, $controller->getRenderedController());
	}

	public function _testExecute_CallsInit() {
		// BROKEN TEST
		$controller = new Index;
		$controller->attachView($this->buildMockViewObject());
		$controller->setAction('indexAction');

		$controller->execute(array());

		$this->assertGreaterThan(0, $controller->getSum());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ViewSet() {
		$controller = new Controller;
		$controller->render();
	}

	public function _testRender_UsesActionForView() {
		// BROKEN TEST
		$viewContent = $this->loadView('indexAction');

		$view = $this->buildMockViewObject();
		$view->setViewPath(DIRECTORY_VIEWS);

		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->setAction('indexAction');
		$controller->render();

		$this->assertEquals($viewContent, $controller->getRenderedView());
	}

	public function _testRenderToBlock_AddsBlock() {
		// BROKEN TEST
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

		$controller = new Controller;
		$controller->setAction($action);

		$this->assertEquals('indexAction', $controller->getAction());
	}

	public function testSetContentType_IsTrimmed() {
		$contentType = ' text/css ';

		$controller = new Controller;
		$controller->setContentType($contentType);

		$this->assertEquals('text/css', $controller->getContentType());
	}

	public function testSetResponseCode_IsInt() {
		$controller = new Controller;
		$controller->setResponseCode(404);

		$this->assertEquals(404, $controller->getResponseCode());
	}

	public function testSetResponseCode_IsCastToInt() {
		$responseCode = 'string';

		$controller = new Controller;
		$controller->setResponseCode($responseCode);

		$this->assertEquals((int)$responseCode, $controller->getResponseCode());
	}

	public function _testGetBlockList_FullList() {
		// BROKEN TEST
		$view = $this->buildMockViewObject();

		$controller = $this->buildMockController();
		$controller->attachView($view);
		$controller->addBlock('abc', '<strong>def</strong>');

		$blockList = $controller->getBlockList();
		$this->assertGreaterThan(0, count($blockList));
	}

	public function testGetBlockList_RequiresView() {
		$controller = new Controller;
		$controller->addBlock('abc', '<strong>def</strong>');

		$blockList = $controller->getBlockList();
		$this->assertEmptyArray($blockList);
	}

	public function testGetBlock_EmptyBlock() {
		$controller = new Controller;
		$this->assertTrue(is_null($controller->getBlock('missing-block')));
	}

	public function testGetContentType_IsOriginallyTextHtml() {
		$controller = new Controller;
		$this->assertEquals('text/html', $controller->getContentType());
	}

	public function testGetHeaderList_FullList() {
		$controller = new Controller;
		$controller->addHeader('X-Powered-By', 'PHP/Jolt');

		$headerList = $controller->getHeaderList();
		$this->assertGreaterThan(0, count($headerList));
	}

	public function testGetHeader_EmptyHeader() {
		$controller = new Controller;
		$this->assertTrue(is_null($controller->getHeader('Content-Type')));
	}

	public function testGetResponseCode_200ByDefault() {
		$controller = new Controller;
		$this->assertEquals(200, $controller->getResponseCode());
	}
}