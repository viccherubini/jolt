<?php

declare(encoding='UTF-8');
namespace JoltTest\Form\Validator;

use \Jolt\Form\Validator\RuleSet,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Validator/RuleSet.php';

class RuleSetTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function test__Construct_RulesMustHaveKeys() {
		$ruleSet = new RuleSet(array('empty', 'email', 'maxlength'));
	}

	public function test__Construct_MessagesExistForSetRules() {
		$ruleSet = new RuleSet(
			array('empty' => false, 'email' => true),
			array('empty' => 'Field can not be empty', 'email' => 'Field must be valid email', 'maxlength' => 'Field less than 128 chars')
		);
		
		$messages = $ruleSet->getMessages();
		$this->assertTrue(array_key_exists('empty', $messages));
		$this->assertTrue(array_key_exists('email', $messages));
		$this->assertFalse(array_key_exists('maxlength', $messages));
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAddMessage_MustHaveKey() {
		$ruleSet = new RuleSet(array('empty' => true));
		$ruleSet->addMessage(NULL, 'Field must not be empty');
	}
	
	public function testIsEmpty_EmptyWhenNoRules() {
		$ruleSet = new RuleSet(array());
		$this->assertTrue($ruleSet->isEmpty());
	}
	
	public function testIsValid_Empty() {
		$validator = new RuleSet(array('empty' => false));
		
		$this->assertTrue($validator->isValid('some value'));
		$this->assertFalse($validator->isValid(NULL));
	}
	
	public function testIsValid_Minlength() {
		$validator = new RuleSet(array('minlength' => 3));
		
		$this->assertTrue($validator->isValid('abcd'));
		$this->assertTrue($validator->isValid('abc'));
		$this->assertFalse($validator->isValid('ac'));
	}

	public function testIsValid_Maxlength() {
		$validator = new RuleSet(array('maxlength' => 128));
		
		$this->assertTrue($validator->isValid('abc'));
		$this->assertTrue($validator->isValid(str_repeat('a', 128)));
		$this->assertFalse($validator->isValid(str_repeat('a', 150)));
	}

	public function testIsValid_Nonzero() {
		$validator = new RuleSet(array('nonzero' => true));
		
		$this->assertTrue($validator->isValid(10));
		$this->assertTrue($validator->isValid(0.01));
		$this->assertFalse($validator->isValid(0));
		$this->assertFalse($validator->isValid('abc'));
	}

	public function testIsValid_Numeric() {
		$validator = new RuleSet(array('numeric' => true));
		
		$this->assertTrue($validator->isValid(10));
		$this->assertTrue($validator->isValid(0.01));
		$this->assertFalse($validator->isValid('str'));
		$this->assertFalse($validator->isValid(array('abc')));
		$this->assertFalse($validator->isValid(array(10)));
		$this->assertFalse($validator->isValid(new \stdClass));
	}

	public function testIsValid_Email() {
		$validator = new RuleSet(array('email' => true));
		
		$this->assertTrue($validator->isValid('email@host'));
		$this->assertTrue($validator->isValid('email@host.com'));
		$this->assertTrue($validator->isValid('email+username@host.com'));
		$this->assertTrue($validator->isValid('email.username@host.com'));
		$this->assertTrue($validator->isValid('email(username)@host.com'));
		$this->assertTrue($validator->isValid('email-username@host.com'));
		$this->assertTrue($validator->isValid('email_username@host.com'));
		$this->assertFalse($validator->isValid('email'));
		$this->assertFalse($validator->isValid('email@'));
		$this->assertFalse($validator->isValid('@host'));
		$this->assertFalse($validator->isValid('vic@host@host'));
	}
	
	public function testIsValid_Inarray() {
		$validator = new RuleSet(array('inarray' => array('a', 'b', 'c', 1, 2, 3)));
		
		$this->assertTrue($validator->isValid('a'));
		$this->assertTrue($validator->isValid('b'));
		$this->assertTrue($validator->isValid('c'));
		$this->assertTrue($validator->isValid(1));
		$this->assertTrue($validator->isValid(2));
		$this->assertTrue($validator->isValid(3));
		$this->assertFalse($validator->isValid('1'));
		$this->assertFalse($validator->isValid('2'));
		$this->assertFalse($validator->isValid('3'));
		$this->assertFalse($validator->isValid(new \stdClass));
	}
	
	public function testIsValid_Regex() {
		$validator = new RuleSet(array('regex' => '#^([a-z]+)([a-z0-9_]*)$#i'));
		
		$this->assertTrue($validator->isValid('a'));
		$this->assertTrue($validator->isValid('A'));
		$this->assertTrue($validator->isValid('a09'));
		$this->assertTrue($validator->isValid('a_09'));
		$this->assertFalse($validator->isValid('a-09'));
		$this->assertFalse($validator->isValid('/a-09/'));
		$this->assertFalse($validator->isValid('/-09/'));
	}
	
	public function testIsValid_Callback() {
		$validator = new RuleSet(array('callback' => function($v) {
				if ( 10 === $v ) {
					return true;
				}
				return false;
			})
		);
		
		$this->assertTrue($validator->isValid(10));
		$this->assertFalse($validator->isValid('a'));
		$this->assertFalse($validator->isValid(10.01));
		$this->assertFalse($validator->isValid(new \stdClass));
		$this->assertFalse($validator->isValid(array('abc')));
	}
	
	public function testGetError_ReturnsErrorForInvalidRule() {
		$field = 'Username';
		$error = 'The field %s can not be empty';
		$errorFull = 'The field Username can not be empty';

		$validator = new RuleSet(array('empty' => true), array('empty' => $error), $field);
		$validator->isValid(NULL);
		
		$this->assertEquals($errorFull, $validator->getError());
	}
	
	public function testGetError_IsNullForNoErrors() {
		$field = 'Username';
		$error = 'The field %s can not be empty';

		$validator = new RuleSet(array('empty' => true), array('empty' => $error), $field);
		$validator->isValid('abc');
		
		$this->assertNull($validator->getError());
	}
	
	
	public function testGetField_ReturnsFieldFromInitialization() {
		$field = 'Username';
		$validator = new RuleSet(array('empty' => true), array('empty' => 'Field can not be empty'), $field);
		
		$this->assertEquals($field, $validator->getField());
	}
}