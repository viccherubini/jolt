<?php

require_once 'PHPUnit/Framework.php';
require_once 'Jolt/Jolt.php';

class JoltCore_Controller_ControllerTest extends PHPUnit_Framework_TestCase {

	public function testEmptyController() {
		$controller = array();
		$this->assertEquals(0, count($controller));
	}

	public function testAisA() {
		$this->assertEquals('a', 'a');
	}
}