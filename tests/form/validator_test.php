<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Validator,
	\JoltTest\TestCase;

require_once 'jolt/form/validator.php';

class ValidatorTest extends TestCase {

	public function test__Construct_RulesCanBeEmpty() {
		$validator = new Validator;
		$this->assertTrue($validator instanceof \Jolt\Form\Validator);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test__Construct_RuleSetElementsMustBeJoltRuleSet() {
		$ruleSet = new \stdClass;
		$ruleSets = array('username' => $ruleSet);

		$validator = new Validator($ruleSets);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function test__Construct_RuleSetMustHaveField() {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array(), array(array()));
		$validator = new Validator(array($ruleSet));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAddRuleSet_RuleSetMustBeJoltRuleSet() {
		$validator = new Validator;
		$validator->addRuleSet('username', new \stdClass);
	}

	public function testAddRuleSet_ReturnsJoltValidatorObject() {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array(), array(array()));
		$validator = new Validator;

		$this->assertTrue($validator->addRuleSet('username', $ruleSet) instanceof \Jolt\Form\Validator);
	}

	public function testCount_ReturnsNumberOfRuleSets() {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array(), array(array()));
		$validator = new Validator;

		$validator->addRuleSet('username', $ruleSet);
		$validator->addRuleSet('age', $ruleSet);

		$this->assertEquals(2, $validator->count());
	}

	public function testIsEmpty_EmptyWhenNoRuleSets() {
		$validator = new Validator;
		$this->assertTrue($validator->isEmpty());
	}

	public function testIsEmpty_NotEmptyWhenRulesSet() {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array(), array(array()));

		$validator = new Validator;
		$validator->addRuleSet('username', $ruleSet);

		$this->assertFalse($validator->isEmpty());
	}

	public function testGetRuleSets_ReturnsArrayOfRuleSets() {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array(), array(array()));

		$validator = new Validator;
		$validator->addRuleSet('username', $ruleSet);
		$validator->addRuleSet('password', $ruleSet);

		$ruleSets = $validator->getRuleSets();
		foreach ( $ruleSets as $ruleSet ) {
			$this->assertTrue($ruleSet instanceof \Jolt\Form\Validator\RuleSet);
		}
	}

}
