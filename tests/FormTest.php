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
	public function testSetValidator_IsArray($data) {
		$form = new Form;
		$form->setValidator($data);
	}

	public function testSetValidator_ReturnsSelf() {
		$form = new Form;
		$this->assertTrue($form->setValidator(array()) instanceof \Jolt\Form);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testSetValidator_ElementsAreRuleSets() {
		$validator = array(
			'name' => new RuleSet(array()),
			'age' => 16,
			'height' => new RuleSet(array())
		);
		
		$form = new Form;
		$form->setValidator($validator);
	}
	
	public function testValidate_TrueIfDataEmpty() {
		$form = new Form;
		$validated = $form->validate();
		
		$this->assertTrue($validated);
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testValidate_ValidatorAndDataEqualLength() {
		$form = new Form;
		
		$data = array('name' => 'Name', 'age' => 16);
		$validator = array('name' => new RuleSet(array()), 'age' => new RuleSet(array()), 'birthday' => new RuleSet(array()));
		
		$form->setData($data);
		$form->setValidator($validator);
		
		$form->validate();
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testValidate_KeysMustMatchInDataAndValidator() {
		$form = new Form;
		
		$data = array('title' => 'Category Title', 'id' => 10);
		$validator = array('title' => new RuleSet(array()), 'name' => new RuleSet(array()));
		
		$form->setData($data);
		$form->setValidator($validator);
		
		$form->validate();
	}
	
	public function testValidate_KeysMatchInDataAndValidator() {
		$form = new Form;
		
		$data = array('title' => 'Category Title', 'id' => 10);
		$validator = array('title' => new RuleSet(array()), 'id' => new RuleSet(array()));
		
		$validated = $form->validate();
		$this->assertTrue($validated);
	}
	
	public function testValidate_TrueIfRuleSetsEmpty() {
		$form = new Form;
		
		$data = array('title' => 'Category Title', 'id' => 10);
		$validator = array('title' => new RuleSet(array()), 'id' => new RuleSet(array()));
		
		$validated = $form->validate();
		$this->assertTrue($validated);
	}
	
	
	
	public function providerInvalidArray() {
		return array(
			array('a'),
			array(10),
			array(new \stdClass)
		);
	}
	
}