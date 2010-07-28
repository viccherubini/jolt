<?php

declare(encoding='UTF-8');
namespace JoltTest\Route;

use \JoltTest\Route\Named\AllTests as NamedTests;

require_once 'Jolt/Route.php';

require_once 'Route/Named/AllTests.php';
require_once 'Route/NamedTest.php';
require_once 'Route/RestfulTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Tests');

		$suite->addTestSuite(NamedTests::suite());
		
		$suite->addTestSuite('\JoltTest\Route\NamedTest');
		$suite->addTestSuite('\JoltTest\Route\RestfulTest');

		return $suite;
	}
}