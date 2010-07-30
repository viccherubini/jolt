<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

require_once 'Jolt/Lib/Library.php';

require_once 'Lib/ArrayTest.php';
require_once 'Lib/CommonTest.php';
require_once 'Lib/CryptTest.php';
require_once 'Lib/InputTest.php';
require_once 'Lib/ValidateTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Lib Tests');

		$suite->addTestSuite('\JoltTest\Lib\ArrayTest');
		$suite->addTestSuite('\JoltTest\Lib\CommonTest');
		$suite->addTestSuite('\JoltTest\Lib\CryptTest');
		$suite->addTestSuite('\JoltTest\Lib\InputTest');
		$suite->addTestSuite('\JoltTest\Lib\ValidateTest');

		return $suite;
	}
	
}