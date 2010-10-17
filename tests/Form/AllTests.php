<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

require_once 'Form/ValidatorTest.php';
require_once 'Form/LoaderTest.php';
require_once 'Form/WriterTest.php';

require_once 'Form/RuleSet/RuleSetTest.php';
require_once 'Form/Writer/DbTest.php';
require_once 'Form/Writer/SessionTest.php';

class AllTests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Form Tests');

		$suite->addTestSuite('\JoltTest\Form\ValidatorTest');
		$suite->addTestSuite('\JoltTest\Form\Validator\RuleSetTest');
		$suite->addTestSuite('\JoltTest\Form\LoaderTest');
		$suite->addTestSuite('\JoltTest\Form\WriterTest');
		$suite->addTestSuite('\JoltTest\Form\Writer\DbTest');
		$suite->addTestSuite('\JoltTest\Form\Writer\SessionTest');

		return $suite;
	}
}