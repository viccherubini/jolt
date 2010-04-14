<?php

require_once 'PHPUnit/Framework.php';

require_once 'Route/RouteTest.php';
require_once 'Route/NamedTest.php';
require_once 'Route/RestfulTest.php';

class Jolt_Route_AllTests {
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('Jolt_Route');
		
		$suite->addTestSuite('Jolt_Route_RouteTest');
		$suite->addTestSuite('Jolt_Route_NamedTest');
		$suite->addTestSuite('Jolt_Route_RestfulTest');

		return $suite;
	}
}