<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

require_once 'Form/ValidatorTest.php';

require_once 'Form/Validator/RuleSetTest.php';
require_once 'Form/Loader/DbTest.php';
require_once 'Form/Writer/DbTest.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Form Tests');

		$suite->addTestSuite('\JoltTest\Form\ValidatorTest');

		$suite->addTestSuite('\JoltTest\Form\Validator\RuleSetTest');
		$suite->addTestSuite('\JoltTest\Form\Loader\DbTest');
		$suite->addTestSuite('\JoltTest\Form\Writer\DbTest');

		return $suite;
	}
}
