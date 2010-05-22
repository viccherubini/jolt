<?php

require_once 'Jolt/Controller.php';

class Jolt_Controller_ControllerTest extends Jolt_TestCase {
	
	public function testConfigIsEmpty() {
		$controller = $this->buildController();
		$this->assertArray($controller->getConfig());
		$this->assertEmptyArray($controller->getConfig());
	}
	
	
	public function testLayoutIsSet() {
		$controller = $this->buildController();
		$controller->setLayout('default');
		
		$this->assertEquals('default', $controller->getLayout());
	}
	
	

	protected function buildController() {
		$controller = $this->getMockForAbstractClass('Jolt_Controller');
		return $controller;
	}
}