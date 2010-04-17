<?php

require_once 'Jolt/Controller.php';

class Jolt_Controller_ControllerTest extends Jolt_TestCase {
	
	public function testConfigIsEmpty() {
		$controller = $this->buildController();
		$this->assertArray($controller->getConfig());
		$this->assertEmptyArray($controller->getConfig());
	}

	protected function buildController() {
		$controller = $this->getMockForAbstractClass('Jolt_Controller');
		return $controller;
	}
}