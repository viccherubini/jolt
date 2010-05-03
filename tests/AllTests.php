<?php

$jolt_test_path = dirname(__FILE__);
$jolt_path = $jolt_test_path . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $jolt_path . PATH_SEPARATOR . $jolt_test_path);

define('TEST_DIRECTORY', $jolt_test_path, false);
define('APPLICATION_DIRECTORY', 'application', false);

require_once 'PHPUnit/Framework.php';

require_once 'TestCase.php';

require_once 'Controller/ControllerTest.php';
require_once 'Dispatcher/DispatcherTest.php';
require_once 'Jolt/JoltTest.php';
require_once 'Registry/RegistryTest.php';
require_once 'Route/AllTests.php';
require_once 'Router/RouterTest.php';
require_once 'TestCase/TestCase.php';
require_once 'View/ViewTest.php';

class Jolt_AllTests {
	
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('Jolt All Tests');
		
		$suite->addTestSuite('Jolt_Controller_ControllerTest');
		$suite->addTestSuite('Jolt_Dispatcher_DispatcherTest');
		$suite->addTestSuite('Jolt_JoltTest');
		$suite->addTestSuite('Jolt_Registry_RegistryTest');
		$suite->addTestSuite(Jolt_Route_AllTests::suite());
		$suite->addTestSuite('Jolt_Router_RouterTest');
		$suite->addTestSuite('Jolt_TestCase_TestCaseTest');
		$suite->addTestSuite('Jolt_View_ViewTest');

		return $suite;
	}
	
}
