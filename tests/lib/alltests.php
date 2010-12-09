<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

require_once 'jolt/lib/library.php';

require_once 'lib/array_test.php';
require_once 'lib/common_test.php';
require_once 'lib/crypt_test.php';
require_once 'lib/input_test.php';
require_once 'lib/validate_test.php';

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
