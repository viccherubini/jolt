<?php

namespace JoltTest;

require_once 'JoltCore/AllTests.php';

class AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltFramework');
		$suite->addTestSuite(JoltCore_AllTests::suite());

		return $suite;
	}
}