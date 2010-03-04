<?php

require_once 'PHPUnit/Framework.php';
require_once 'JoltCore/AllTests.php';
require_once 'JoltApp/AllTests.php';

class AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltFramework');
		$suite->addTestSuite(JoltCore_AllTests::suite());
		$suite->addTestSuite(JoltApp_AllTests::suite());

		return $suite;
	}
}