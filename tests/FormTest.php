<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Form,
	\Jolt\FormController,
	\JoltTest\TestCase;

require_once 'Jolt/Form.php';
require_once 'Jolt/FormController.php';
require_once 'Jolt/Form/Loader.php';
require_once 'Jolt/Form/Writer.php';

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
	 */
	public function testAttachException_IsException() {
		$form = new Form;
		$form->attachException(NULL);
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
	 */
	public function testAttachLoader_IsLoader() {
		$form = new Form;
		$form->attachLoader(NULL);
	}

	public function testAttachLoader_ReturnsJoltFormObject() {
		$loader = $this->getMock('\Jolt\Form\Loader');
		$form = new Form;

		$this->assertTrue($form->attachLoader($loader) instanceof \Jolt\Form);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachWriter_IsWriter() {
		$form = new Form;
		$form->attachWriter(NULL);
	}

	public function testAttachWriter_ReturnsJoltFormObject() {
		$loader = $this->getMock('\Jolt\Form\Writer');
		$form = new Form;

		$this->assertTrue($form->attachWriter($loader) instanceof \Jolt\Form);
	}













	public function testLoad_ReturnsFalseIfNoLoader() {

	}

	public function testWrite_ReturnsFalseIfNoWriter() {

	}

	public function testValidate_ReturnsFalseIfNoValidator() {

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