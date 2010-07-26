<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'ClientTest.php';
require_once 'ControllerTest.php';
require_once 'DispatcherTest.php';
require_once 'JoltTest.php';
require_once 'RegistryTest.php';
require_once 'RouteTest.php';
require_once 'Route/AllTests.php';
require_once 'RouterTest.php';
require_once 'TestCaseTest.php';
require_once 'ViewTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt All Tests');
		
		$suite->addTestSuite('\JoltTest\ClientTest');
		$suite->addTestSuite('\JoltTest\ControllerTest');
		$suite->addTestSuite('\JoltTest\DispatcherTest');
		$suite->addTestSuite('\JoltTest\JoltTest');
		$suite->addTestSuite('\JoltTest\RegistryTest');
		$suite->addTestSuite('\JoltTest\RouteTest');
		$suite->addTestSuite(Route_AllTests::suite());
		$suite->addTestSuite('\JoltTest\RouterTest');
		$suite->addTestSuite('\JoltTest\TestCaseTest');
		$suite->addTestSuite('\JoltTest\ViewTest');

		return $suite;
	}
	
}