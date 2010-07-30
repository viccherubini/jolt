<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \JoltTest\Route\AllTests as RouteTests,
	\JoltTest\Lib\AllTests as LibTests;

require_once 'ClientTest.php';
require_once 'ControllerTest.php';
require_once 'DispatcherTest.php';
require_once 'JoltTest.php';
require_once 'Lib/AllTests.php';
require_once 'RegistryTest.php';
require_once 'RouteTest.php';
require_once 'Route/AllTests.php';
require_once 'RouterTest.php';
require_once 'TestCaseTest.php';
require_once 'ViewTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Tests');
		
		$suite->addTestSuite('\JoltTest\DispatcherTest');
		$suite->addTestSuite(LibTests::suite());
		$suite->addTestSuite('\JoltTest\RegistryTest');
		$suite->addTestSuite('\JoltTest\RouteTest');
		$suite->addTestSuite(RouteTests::suite());
		$suite->addTestSuite('\JoltTest\RouterTest');
		$suite->addTestSuite('\JoltTest\TestCaseTest');

		return $suite;
	}
	
}