<?php

require_once 'PHPUnit/Framework.php';

class JoltCore_Router_RouterTest extends PHPUnit_Framework_TestCase {

	public function testEmptyRouter() {
		$router = array();
		$this->assertEquals(0, count($router));
	}
}