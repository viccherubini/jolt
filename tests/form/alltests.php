<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

require_once 'form/validator_test.php';

//require_once 'form/validator/ruleset_test.php';
require_once 'form/loader/db_test.php';
require_once 'form/writer/db_test.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Form Tests');

		$suite->addTestSuite('\JoltTest\Form\ValidatorTest');

		//$suite->addTestSuite('\JoltTest\Form\Validator\RuleSetTest');
		$suite->addTestSuite('\JoltTest\Form\Loader\DbTest');
		$suite->addTestSuite('\JoltTest\Form\Writer\DbTest');

		return $suite;
	}
}
