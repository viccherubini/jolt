<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \JoltTest\Controller\AllTests as ControllerTests,
	\JoltTest\Form\AllTests as FormTests,
	\JoltTest\Lib\AllTests as LibTests,
	\JoltTest\Route\AllTests as RouteTests;

require_once 'Controller/AllTests.php';
require_once 'Form/AllTests.php';
require_once 'Lib/AllTests.php';
require_once 'Route/AllTests.php';

require_once 'ClientTest.php';
require_once 'ControllerTest.php';
require_once 'DispatcherTest.php';
require_once 'FormTest.php';
require_once 'FormControllerTest.php';
require_once 'JoltTest.php';
require_once 'MiscTest.php';
require_once 'RegistryTest.php';
require_once 'RouteTest.php';
require_once 'RouterTest.php';
require_once 'SettingsTest.php';
require_once 'TestCaseTest.php';
require_once 'ViewTest.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Tests');

		$suite->addTestSuite(ControllerTests::suite());
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