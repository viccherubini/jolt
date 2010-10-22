<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Form,
	\Jolt\FormController,
	\JoltTest\TestCase;

require_once 'Jolt/Form.php';
require_once 'Jolt/FormController.php';
require_once 'Jolt/Form/Loader.php';
require_once 'Jolt/Form/Loader/Db.php';
require_once 'Jolt/Form/Writer.php';
require_once 'Jolt/Form/Writer/Db.php';
require_once 'Jolt/Form/Validator.php';
require_once 'Jolt/Form/Validator/RuleSet.php';
require_once 'Jolt/Form/Writer.php';

class FormTest extends TestCase {

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

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachValidator_IsWriter() {
		$form = new Form;
		$form->attachValidator(NULL);
	}

	public function testAttachValidator_ReturnsJoltFormObject() {
		$validator = $this->getMock('\Jolt\Form\Validator');
		$form = new Form;

		$this->assertTrue($form->attachValidator($validator) instanceof \Jolt\Form);
	}

	public function testLoad_ReturnsFalseIfNoLoader() {
		$form = new Form;
		$loaded = $form->load();

		$this->assertFalse($loaded);
	}

	public function testLoad_ReturnsFalseIfFormMissing() {
		$loader = $this->buildMockLoader(false);

		$form = new Form;
		$form->attachLoader($loader);
		$form->setId('form1');
		$form->setName('article');

		$loaded = $form->load();

		$this->assertFalse($loaded);
	}

	public function testLoad_FindsForm() {
		$loader = $this->buildMockLoader(true);

		$form = new Form;
		$form->attachLoader($loader);
		$form->setId('form1');
		$form->setName('article');

		$loaded = $form->load();

		$this->assertTrue($loaded);
	}

	public function testWrite_ReturnsFalseIfNoWriter() {
		$form = new Form;
		$written = $form->write();

		$this->assertFalse($written);
	}

	public function testWrite_ReturnsFalseOnFailure() {
		$writer = $this->buildMockWriter(false);

		$form = new Form;
		$form->attachWriter($writer);
		$form->setId('form1');
		$form->setName('article');
		$form->setData(array('title' => 'article title here'));

		$written = $form->write();

		$this->assertFalse($written);
	}

	public function testValidate_ReturnsFalseIfNoValidator() {
		$form = new Form;
		$validated = $form->validate();

		$this->assertFalse($validated);
	}

	public function testValidate_ReturnsTrueIfNoRuleSets() {
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty'));

		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(true));

		$form = new Form;
		$form->attachValidator($validator);

		$validated = $form->validate();
		$this->assertTrue($validated);
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testValidate_ThrowsExceptionIfInvalid() {
		$validator = $this->buildMockValidatorWithRuleSet(false);

		$form = new Form;
		$form->attachValidator($validator);
		$form->setData(array('title' => ''));

		$form->validate();
	}

	/**
	 * @expectedException \JoltTest\Exception
	 */
	public function testValidate_ThrowsCustomExceptionIfInvalid() {
		$validator = $this->buildMockValidatorWithRuleSet(false);

		$form = new Form;
		$form->attachException(new \JoltTest\Exception);
		$form->attachValidator($validator);
		$form->setData(array('title' => ''));

		$form->validate();
	}

	public function testValidate_ReturnsTrueIfValid() {
		$validator = $this->buildMockValidatorWithRuleSet(true);

		$form = new Form;
		$form->attachValidator($validator);
		$form->setData(array('title' => 'title'));

		$valid = $form->validate();
		$this->assertTrue($valid);
	}

	public function providerInvalidArray() {
		return array(
			array('a'),
			array(10),
			array(new \stdClass)
		);
	}


	private function buildMockLoader($loaded) {
		$pdoMock = $this->getMockForAbstractClass('\PDO', array('sqlite::memory:'));

		$loader = $this->getMock('\Jolt\Form\Loader\Db', array('attachPdo', 'setId', 'setName', 'getData', 'load'));

		$loader->expects($this->once())
			->method('attachPdo')
			->with($this->equalTo($pdoMock))
			->will($this->returnValue($loader));

		$loader->expects($this->once())
			->method('setId')
			->with($this->equalTo('form1'))
			->will($this->returnValue($loader));

		$loader->expects($this->once())
			->method('setName')
			->with($this->equalTo('article'))
			->will($this->returnValue($loader));

		$loader->expects($this->any())
			->method('getData')
			->will($this->returnValue(array('title' => 'article title here')));

		$loader->expects($this->once())
			->method('load')
			->will($this->returnValue($loaded));

		$loader->attachPdo($pdoMock);

		return $loader;
	}

	private function buildMockWriter($written) {
		$pdoMock = $this->getMockForAbstractClass('\PDO', array('sqlite::memory:'));

		$writer = $this->getMock('\Jolt\Form\Writer\Db', array('attachPdo', 'setId', 'setName', 'setData', 'write'));

		$writer->expects($this->once())
			->method('attachPdo')
			->with($this->equalTo($pdoMock))
			->will($this->returnValue($writer));

		$writer->expects($this->once())
			->method('setId')
			->with($this->equalTo('form1'))
			->will($this->returnValue($writer));

		$writer->expects($this->once())
			->method('setName')
			->with($this->equalTo('article'))
			->will($this->returnValue($writer));

		$writer->expects($this->once())
			->method('setData')
			->will($this->returnValue($writer));

		$writer->expects($this->once())
			->method('write')
			->will($this->returnValue($written));

		$writer->attachPdo($pdoMock);

		return $writer;
	}

	private function buildMockValidatorWithRuleSet($isValid) {
		$ruleSet = $this->getMock('\Jolt\Form\Validator\RuleSet', array('isValid', 'getError'), array(array()));

		$ruleSet->expects($this->once())
			->method('isValid')
			->will($this->returnValue($isValid));

		$ruleSet->expects($this->any())
			->method('getError')
			->will($this->returnValue('An error in field: title'));

		$ruleSets = array('title' => $ruleSet);

		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'getRuleSets'));

		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));

		$validator->expects($this->once())
			->method('getRuleSets')
			->will($this->returnValue($ruleSets));

		return $validator;
	}

}

class Exception extends \Exception {

}