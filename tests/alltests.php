<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \jolt_test\controller\alltests as controller_tests,
	\JoltTest\Form\AllTests as FormTests,
	\JoltTest\Lib\AllTests as LibTests,
	\JoltTest\Route\AllTests as RouteTests;

require_once 'controller/alltests.php';
require_once 'form/alltests.php';
require_once 'lib/alltests.php';
require_once 'route/alltests.php';

require_once 'client_test.php';
require_once 'controller_test.php';
require_once 'dispatcher_test.php';
require_once 'form_test.php';
require_once 'form_controller_test.php';
require_once 'jolt_test.php';
require_once 'misc_test.php';
require_once 'registry_test.php';
require_once 'route_test.php';
require_once 'router_test.php';
require_once 'settings_test.php';
require_once 'testcase_test.php';
require_once 'view_test.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('jolt_test_suite');

		$suite->addTestSuite(controller_tests::suite());
		$suite->addTestSuite(FormTests::suite());
		$suite->addTestSuite(LibTests::suite());
		$suite->addTestSuite(RouteTests::suite());

		$suite->addTestSuite('\JoltTest\ControllerTest');
		$suite->addTestSuite('\JoltTest\ClientTest');
		$suite->addTestSuite('\JoltTest\DispatcherTest');
		$suite->addTestSuite('\JoltTest\FormTest');
		$suite->addTestSuite('\JoltTest\FormControllerTest');
		$suite->addTestSuite('\JoltTest\MiscTest');
		$suite->addTestSuite('\JoltTest\RegistryTest');
		$suite->addTestSuite('\JoltTest\RouteTest');
		$suite->addTestSuite('\JoltTest\RouterTest');
		$suite->addTestSuite('\JoltTest\SettingsTest');
		$suite->addTestSuite('\JoltTest\TestCaseTest');
		$suite->addTestSuite('\JoltTest\ViewTest');

		return $suite;
	}

}
