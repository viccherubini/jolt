<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Client/ClientTest.php';
require_once 'Controller/ControllerTest.php';
require_once 'Dispatcher/DispatcherTest.php';
require_once 'Jolt/JoltTest.php';
require_once 'Registry/RegistryTest.php';
require_once 'Route/AllTests.php';
require_once 'Router/RouterTest.php';
require_once 'TestCase/TestCase.php';
require_once 'View/ViewTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt All Tests');
		
		$suite->addTestSuite('\Jolt\ClientTest');
		$suite->addTestSuite('\Jolt\ControllerTest');
		$suite->addTestSuite('\Jolt\DispatcherTest');
		$suite->addTestSuite('\Jolt\JoltTest');
		$suite->addTestSuite('\Jolt\RegistryTest');
		$suite->addTestSuite(Route_AllTests::suite());
		$suite->addTestSuite('\Jolt\RouterTest');
		$suite->addTestSuite('\Jolt\TestCaseTest');
		$suite->addTestSuite('\Jolt\ViewTest');

		return $suite;
	}
	
}