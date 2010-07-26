<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class ControllerTest extends TestCase {
	
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
		$controller = $this->getMockForAbstractClass('\Jolt\Controller');
		return $controller;
	}
}