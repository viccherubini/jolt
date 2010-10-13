<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

require_once 'Form/ValidatorTest.php';
require_once 'Form/RuleSet/RuleSetTest.php';

class AllTests {
	
	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Form Tests');
		
		$suite->addTestSuite('\JoltTest\Form\ValidatorTest');
		$suite->addTestSuite('\JoltTest\Form\Validator\RuleSetTest');

		return $suite;
	}
}