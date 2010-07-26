<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'PHPUnit/Framework.php';

require_once 'Route/NamedTest.php';
require_once 'Route/RestfulTest.php';

class Route_AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Tests');
		
		$suite->addTestSuite('\JoltTest\Route\NamedTest');
		$suite->addTestSuite('\JoltTest\Route\RestfulTest');

		return $suite;
	}
}