<?php

declare(encoding='UTF-8');
namespace JoltTest\Route\Named;

require_once 'Jolt/Route/Named.php';

require_once 'DeleteTest.php';
require_once 'GetTest.php';
require_once 'PostTest.php';
require_once 'PutTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Named Tests');
		
		$suite->addTestSuite('\JoltTest\Route\Named\DeleteTest');
		$suite->addTestSuite('\JoltTest\Route\Named\GetTest');
		$suite->addTestSuite('\JoltTest\Route\Named\PostTest');
		$suite->addTestSuite('\JoltTest\Route\Named\PutTest');

		return $suite;
	}
}