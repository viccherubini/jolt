<?php

require_once 'PHPUnit/Framework.php';
require_once 'RouteTest.php';
require_once 'Route/RestfulTest.php';
require_once 'Route/NamedTest.php';

class JoltCore_Route_AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltCore_Route');
		$suite->addTestSuite('JoltCore_Route_RouteTest');
		$suite->addTestSuite('JoltCore_Route_Route_RestfulTest');
		$suite->addTestSuite('JoltCore_Route_Route_NamedTest');

		return $suite;
	}
}