<?php

require_once 'PHPUnit/Framework.php';
require_once 'Router/RouterTest.php';
require_once 'Controller/ControllerTest.php';

class JoltCore_AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltCore');
		$suite->addTestSuite('JoltCore_Router_RouterTest');
		$suite->addTestSuite('JoltCore_Controller_ControllerTest');
		
		return $suite;
	}

}
