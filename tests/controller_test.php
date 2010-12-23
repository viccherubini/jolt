<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\controller,
	\jolt_test\testcase;

//require_once(DIRECTORY_CONTROLLERS . DS . 'index.php');

class controller_test extends testcase {

	public function test___set__requires_attached_view_object() {
		$controller = new controller;
		$controller->name = 'Vic';

		$this->assertTrue(is_null($controller->name));
	}

	public function test___set__sets_value_in_attached_view_object() {
		$name = 'name';
		$view = $this->getMock('\Jolt\View', array('__get'));
		$view->expects($this->once())
			->method('__get')
			->will($this->returnArgument(0));

		$controller = new controller;
		$controller->attach_view($view);
		$controller->name = $name;

		$this->assertEquals($name, $controller->name);
	}

	public function test_add_header__adds_new_header() {
		$controller = new controller;
		$controller->add_header('X-Powered-By', 'PHP/Jolt');

		$this->assertEquals('PHP/Jolt', $controller->get_header('X-Powered-By'));
	}

	public function test_add_header__content_type_header_set_internally() {
		$controller = new controller;
		$controller->add_header('Content-Type', 'text/css');

		$this->assertEquals(0, count($controller->get_headers()));
		$this->assertEquals('text/css', $controller->get_content_type());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function test_attach_view__requires_jolt_view($view) {
		$controller = new controller;
		$controller->attach_view($view);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function test_execute__requires_action() {
		$controller = new controller;
		$controller->execute(array());
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function test_execute__requires_action_to_exist() {
		$controller = new Controller;
		$controller->set_action('missingAction');
		$controller->execute(10);
	}

	public function _test_execute__ParamCount() {
		//$action = 'paramAction';

		//$controller = new Index;
		//$controller->setAction($action);

		//$reflectionAction = new \ReflectionMethod($controller, $action);
		//$expectedParamCount = $reflectionAction->getNumberOfRequiredParameters();

		//$actualParamCount = $controller->execute(array(10));

		//$this->assertEquals($expectedParamCount, $actualParamCount);
	}

	public function _testExecute_StaticAction() {
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
	public function test_render__requires_attached_jolt_view() {
		$controller = new controller;
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

	public function _testSetAction_IsTrimmed() {
		$action = ' indexAction ';

		$controller = new Controller;
		$controller->setAction($action);

		$this->assertEquals('indexAction', $controller->getAction());
	}

	public function _testSetContentType_IsTrimmed() {
		$contentType = ' text/css ';

		$controller = new Controller;
		$controller->setContentType($contentType);

		$this->assertEquals('text/css', $controller->getContentType());
	}

	public function _testSetResponseCode_IsInt() {
		$controller = new Controller;
		$controller->setResponseCode(404);

		$this->assertEquals(404, $controller->getResponseCode());
	}

	public function _testSetResponseCode_IsCastToInt() {
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

	public function _testGetBlockList_RequiresView() {
		$controller = new Controller;
		$controller->addBlock('abc', '<strong>def</strong>');

		$blockList = $controller->getBlockList();
		$this->assertEmptyArray($blockList);
	}

	public function _testGetBlock_EmptyBlock() {
		$controller = new Controller;
		$this->assertTrue(is_null($controller->getBlock('missing-block')));
	}

	public function _testGetContentType_IsOriginallyTextHtml() {
		$controller = new Controller;
		$this->assertEquals('text/html', $controller->getContentType());
	}

	public function _testGetHeaderList_FullList() {
		$controller = new Controller;
		$controller->addHeader('X-Powered-By', 'PHP/Jolt');

		$headerList = $controller->getHeaderList();
		$this->assertGreaterThan(0, count($headerList));
	}

	public function _testGetHeader_EmptyHeader() {
		$controller = new Controller;
		$this->assertTrue(is_null($controller->getHeader('Content-Type')));
	}

	public function _testGetResponseCode_200ByDefault() {
		$controller = new Controller;
		$this->assertEquals(200, $controller->getResponseCode());
	}
}