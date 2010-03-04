<?php

require_once 'PHPUnit/Framework.php';
require_once 'Jolt/Controller.php';

class JoltCore_Controller_ControllerTest extends PHPUnit_Framework_TestCase {
	
	public function testConfigIsEmpty() {
		$controller = $this->buildController();
		
		$this->assertEquals(is_array(array()), is_array($controller->getConfig()));
		$this->assertEquals(0, count($controller->getConfig()));
	}


	protected function buildController() {
		$controller = $this->getMockForAbstractClass('Jolt_Controller');
		return $controller;
	}
}