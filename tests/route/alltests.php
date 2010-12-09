<?php

declare(encoding='UTF-8');
namespace JoltTest\Route;

use \JoltTest\Route\Named\AllTests as NamedTests;

require_once 'jolt/route.php';

require_once 'route/named/alltests.php';
require_once 'route/named_test.php';
require_once 'route/restful_test.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Tests');

		$suite->addTestSuite(NamedTests::suite());

		$suite->addTestSuite('\JoltTest\Route\NamedTest');
		$suite->addTestSuite('\JoltTest\Route\RestfulTest');

		return $suite;
	}
}