<?php

declare(encoding='UTF-8');
namespace jolt_test;

class testcase extends \PHPUnit_Framework_TestCase {

	public function test_true() {
		$this->assertTrue(true);
	}

	public static function assertArray($a, $message = '') {
		self::assertThat(is_array($a), self::isTrue(), $message);
	}

	public static function assertEmptyArray($a, $message = '') {
		self::assertArray($a);
		self::assertEquals(0, count($a), $message);
	}

	public static function assertEmpty($v, $message = '') {
		if ( is_array($v) ) {
			self::assertEmptyArray($v);
		} else {
			self::assertTrue(empty($v));
		}
	}

	protected function loadView($viewName) {
		$viewFile = DIRECTORY_VIEWS . DS . $viewName . '.phtml';
		$viewContent = ( is_file($viewFile) ? file_get_contents($viewFile) : NULL );

		return $viewContent;
	}

	protected function buildController($name) {
		$controllerFile = DIRECTORY_CONTROLLERS . DS . strtolower($name) . \Jolt\Controller::EXT;
		if ( !is_file($controllerFile) ) {
			throw new \Jolt\Exception('testcase_controller_not_found');
		}

		require_once $controllerFile;

		$controllerName = "\\JoltApp\\{$name}";
		$controller = new $controllerName;
		return $controller;
	}

	protected function buildMockAbstractRoute() {
		$mock = $this->getMockForAbstractClass('\Jolt\Route');
		return $mock;
	}

	protected function buildMockClient() {
		$mock = $this->getMock('\Jolt\Client');

		return $mock;
	}

	protected function buildMockSettings(array $cfg) {
		$mock = $this->getMock('\Jolt\Settings');

		foreach ( $cfg as $k => $v ) {
			$mock->$k = $v;
		}

		return $mock;
	}

	protected function buildMockController() {
		$mock = $this->getMock('\Jolt\Controller');
		return $mock;
	}

	protected function buildMockControllerLocator() {
		$controller = $this->buildMockController();

		$mock = $this->getMock('\Jolt\Controller\Locator', array('load'));
		$mock->expects($this->any())
			->method('load')
			->will($this->returnValue($controller));

		return $mock;
	}

	protected function buildMockDispatcher() {
		$mock = $this->getMock('\Jolt\Dispatcher');

		return $mock;
	}

	protected function buildMockEmptyRouter() {
		$mock = $this->getMock('\Jolt\Router', array('getRouteCount'));
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(0));

		return $mock;
	}

	protected function buildMockNamedRoute($requestMethod, $route, $controller, $action) {
		$mock = $this->getMock('\Jolt\Route\Named',
			array('getRoute', 'getAction', 'getController', 'getRequestMethod'),
			array($requestMethod, $route, $controller, $action)
		);

		$mock->expects($this->any())
			->method('getRoute')
			->will($this->returnValue($route));

		$mock->expects($this->any())
			->method('getAction')
			->will($this->returnValue($action));

		$mock->expects($this->any())
			->method('getController')
			->will($this->returnValue($controller));

		$mock->expects($this->any())
			->method('getRequestMethod')
			->will($this->returnValue($requestMethod));

		return $mock;
	}

	protected function buildMockRestfulRoute($route, $resource) {
		$mock = $this->getMock('\Jolt\Route\Restful',
			array('getRoute', 'getResource'),
			array($route, $resource)
		);

		$mock->expects($this->any())
			->method('getRoute')
			->will($this->returnValue($route));

		$mock->expects($this->any())
			->method('getResource')
			->will($this->returnValue($resource));

		return $mock;
	}

	protected function buildMockRouter() {
		$namedRouteList = array($this->buildMockNamedRoute('/abc', 'Controller', 'action'));

		$mock = $this->getMock('\Jolt\Router', array('getRouteCount'));
		$mock->expects($this->any())
			->method('getRouteCount')
			->will($this->returnValue(count($namedRouteList)));

		return $mock;
	}

	protected function buildMockView() {
		$mock = $this->getMock('\Jolt\View');

		return $mock;
	}

	protected function buildMockViewObject() {
		$mock = $this->getMockForAbstractClass('\Jolt\View');

		return $mock;
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