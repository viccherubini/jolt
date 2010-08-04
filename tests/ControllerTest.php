<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Controller,
	\JoltTest\TestCase;

require_once 'Jolt/Controller.php';

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
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltObject
	 */
	public function testAttachView_IsView($view) {
		$controller = $this->buildMockController();
		
		$controller->attachView($view);
	}
	
	public function testExecute_ViewSet() {
		
	}
	
	public function testExecute_ActionSet() {
		
		
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