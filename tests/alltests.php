<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt_test\controller\alltests as controller_tests,
	\jolt_test\form\alltests as form_tests,
	\jolt_test\route\alltests as route_tests;

require_once('controller/alltests.php');
require_once('form/alltests.php');
require_once('route/alltests.php');

require_once('client_test.php');
require_once('controller_test.php');
require_once('dispatcher_test.php');
require_once('form_test.php');
require_once('form_controller_test.php');
require_once('jolt_test.php');
require_once('misc_test.php');
require_once('registry_test.php');
require_once('route_test.php');
require_once('router_test.php');
require_once('settings_test.php');
require_once('testcase_test.php');
require_once('view_test.php');

class alltests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('jolt_test_suite');

		$suite->addTestSuite(controller_tests::suite());
		$suite->addTestSuite(form_tests::suite());
		$suite->addTestSuite(route_tests::suite());

		$suite->addTestSuite('\jolt_test\controller_test');
		$suite->addTestSuite('\jolt_test\client_test');
		$suite->addTestSuite('\jolt_test\dispatcher_test');
		$suite->addTestSuite('\jolt_test\form_test');
		$suite->addTestSuite('\jolt_test\form_controller_test');
		$suite->addTestSuite('\jolt_test\misc_test');
		$suite->addTestSuite('\jolt_test\registry_test');
		$suite->addTestSuite('\jolt_test\route_test');
		$suite->addTestSuite('\jolt_test\router_test');
		$suite->addTestSuite('\jolt_test\settings_test');
		$suite->addTestSuite('\jolt_test\testcase_test');
		$suite->addTestSuite('\jolt_test\view_test');

		return $suite;
	}

}
