<?php

namespace JoltTest;
use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class JoltCore_Controller_ControllerTest extends TestCase {
	
	public function testConfigIsEmpty() {
		$controller = $this->buildController();
		
		$this->assertArray($controller->getConfig());
		$this->assertEmptyArray($controller->getConfig());
	}

	protected function buildController() {
		$controller = $this->getMockForAbstractClass('\Jolt\Controller');
		return $controller;
	}
}