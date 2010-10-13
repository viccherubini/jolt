<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Form,
	\Jolt\Form\RuleSet,
	\JoltTest\TestCase;

require_once 'Jolt/Form.php';

class FormTest extends TestCase {
	
	public function testAddMessage_OverwritesExistingMessages() {
		$field = 'username';
		$msg1 = 'Error with the username';
		$msg2 = 'Make the username nonempty';
		
		$form = new Form;
		
		$form->addMessage($field, $msg1);
		$this->assertEquals($msg1, $form->message($field));
		
		$form->addMessage($field, $msg2);
		$this->assertEquals($msg2, $form->message($field));
	}
	
	public function testMessage_ReturnsNullForMissingMessage() {
		$form = new Form;
		$this->assertNull($form->message('username'));
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidArray
	 */
	public function testAttachException_IsException($exception) {
		$form = new Form;
		$form->attachException($exception);
	}
	
	public function testAttachException_CanAttachPrebuiltException() {
		$msg = 'An error occurred';
		$exception = new \Exception($msg);
		
		$form = new Form;
		$this->assertTrue($form->attachException($exception) instanceof \Jolt\Form);
		
		$exception = $form->getException();
		$this->assertEquals($msg, $exception->getMessage());
	}
	
	
	
	
	
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidArray
	 */
	public function testSetData_IsArray($data) {
		$form = new Form;
		$form->setData($data);
	}
	
	public function testSetData_ReturnsSelf() {
		$form = new Form;
		$this->assertTrue($form->setData(array()) instanceof \Jolt\Form);
	}


	
	public function providerInvalidArray() {
		return array(
			array('a'),
			array(10),
			array(new \stdClass)
		);
	}
	
}