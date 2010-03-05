<?php

require_once 'JoltCore/TestCase.php';

/**
 * @see Jolt_Controller
 */
require_once 'Jolt/Controller.php';

class JoltCore_Controller_ControllerTest extends JoltCore_TestCase {
	
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