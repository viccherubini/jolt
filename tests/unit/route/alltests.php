<?php

declare(encoding='UTF-8');
namespace jolt_test\route;

use \jolt_test\route\named\alltests as named_tests;

require_once('jolt/route.php');

require_once('route/named/alltests.php');
require_once('route/named_test.php');
require_once('route/restful_test.php');

class alltests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Tests');

		$suite->addTestSuite(named_tests::suite());

		$suite->addTestSuite('\jolt_test\route\named_test');
		$suite->addTestSuite('\jolt_test\route\restful_test');

		return $suite;
	}
}
