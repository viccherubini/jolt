<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Form,
	\Jolt\Form\RuleSet,
	\JoltTest\TestCase;

require_once 'Jolt/Form.php';
require_once 'Jolt/Form/RuleSet.php';

class FormTest extends TestCase {
	
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
	
	
	public function providerInvalidArray() {
		return array(
			array('a'),
			array(10),
			array(new \stdClass)
		);
	}
	
}