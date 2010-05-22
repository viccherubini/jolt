<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'PHPUnit/Framework.php';

require_once 'Route/RouteTest.php';
require_once 'Route/NamedTest.php';
require_once 'Route/RestfulTest.php';

class Route_AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Tests');
		
		$suite->addTestSuite('\Jolt\RouteTest');
		$suite->addTestSuite('\Jolt\Route_NamedTest');
		$suite->addTestSuite('\Jolt\Route_RestfulTest');

		return $suite;
	}
}