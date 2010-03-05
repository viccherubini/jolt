<?php

require_once 'PHPUnit/Framework.php';

require_once 'Router/RouterTest.php';
require_once 'Controller/ControllerTest.php';
require_once 'Route/AllTests.php';

class JoltCore_AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltCore');
		$suite->addTestSuite('JoltCore_Router_RouterTest');
		$suite->addTestSuite('JoltCore_Controller_ControllerTest');
		$suite->addTestSuite(JoltCore_Route_AllTests::suite());

		return $suite;
	}
}