<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\form,
	\jolt\form_controller,
	\jolt_test\testcase;

require_once('jolt/form_controller.php');
require_once('jolt/form/loader.php');
require_once('jolt/form/writer.php');

class form_test extends testcase {

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachException_RequiresExceptionArgument() {
		$form = new Form;
		$form->attachException(NULL);
	}

	public function testAttachException_IsExceptionArgument() {
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
	public function testAttachLoader_RequiresLoaderArgument() {
		$form = new Form;
		$form->attachLoader(NULL);
	}

	public function testAttachLoader_IsLoaderArgument() {
		$loader = $this->getMock('\Jolt\Form\Loader');
		$form = new Form;

		$this->assertTrue($form->attachLoader($loader) instanceof \Jolt\Form);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachWriter_RequiresWriterArgument() {
		$form = new Form;
		$form->attachWriter(NULL);
	}

	public function testAttachWriter_IsWriterArgument() {
		$loader = $this->getMock('\Jolt\Form\Writer');
		$form = new Form;

		$this->assertTrue($form->attachWriter($loader) instanceof \Jolt\Form);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAttachValidator_RequiresValidatorObject() {
		$form = new Form;
		$form->attachValidator(NULL);
	}

	public function testAttachValidator_IsValidatorArgument() {
		$validator = $this->getMock('\Jolt\Form\Validator');
		$form = new Form;

		$this->assertTrue($form->attachValidator($validator) instanceof \Jolt\Form);
	}

	public function testLoad_RequiresAttachedLoader() {
		$form = new Form;
		$loaded = $form->load();

		$this->assertFalse($loaded);
	}

	public function testLoad_Fails() {
		$loader = $this->buildMockFailingLoader();

		$form = new Form;
		$form->attachLoader($loader);
		$form->setId('form1');
		$form->setName('article');

		$loaded = $form->load();

		$this->assertFalse($loaded);
	}

	public function testLoad_Successful() {
		$loader = $this->buildMockSuccessfulLoader();

		$form = new Form;
		$form->attachLoader($loader);
		$form->setId('form1');
		$form->setName('article');

		$loaded = $form->load();

		$this->assertTrue($loaded);
	}

	public function testWrite_RequiresAttachedWriter() {
		$form = new Form;
		$written = $form->write();

		$this->assertFalse($written);
	}

	public function testWrite_Fails() {
		$writer = $this->buildMockFailingWriter();

		$form = new Form;
		$form->attachWriter($writer);
		$form->setId('form1');
		$form->setName('article');
		$form->setData(array('title' => 'article title here'));

		$written = $form->write();

		$this->assertFalse($written);
	}

	public function testWrite_Successful() {
		$writer = $this->buildMockSuccessfulWriter();

		$form = new Form;
		$form->attachWriter($writer);
		$form->setId('form1');
		$form->setName('article');
		$form->setData(array('title' => 'article title here'));

		$written = $form->write();

		$this->assertTrue($written);
	}

	public function testValidate_RequiresAttachedValidator() {
		$form = new Form;
		$validated = $form->validate();

		$this->assertFalse($validated);
	}

	public function testValidate_RequiresAttachedNonEmptyValidator() {
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
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'getRuleSet'));
		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));
		$validator->expects($this->once())
			->method('getRuleSet')
			->will($this->returnValue(array()));

		$form = new Form;
		$form->attachValidator($validator);
		$form->addError('title', 'An error occurred when attempting to do something to the title');

		$form->validate();
	}

	/**
	 * @expectedException \exception
	 */
	public function testValidate_ThrowsCustomExceptionIfInvalid() {
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'getRuleSet'));
		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));
		$validator->expects($this->once())
			->method('getRuleSet')
			->will($this->returnValue(array()));

		$form = new Form;
		$form->attachException(new \exception);
		$form->attachValidator($validator);
		$form->addError('title', 'An error occurred when attempting to do something to the title');

		$form->validate();
	}

	public function testValidate_Successful() {
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'getRuleSet'));
		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));
		$validator->expects($this->once())
			->method('getRuleSet')
			->will($this->returnValue(array()));

		$form = new Form;
		$form->attachValidator($validator);

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

	private function buildMockFailingLoader() {
		$loader = $this->getMockForAbstractClass('\Jolt\Form\Loader');
		$loader->expects($this->once())
			->method('load')
			->will($this->returnValue(false));

		return $loader;
	}

	private function buildMockSuccessfulLoader() {
		$loader = $this->getMockForAbstractClass('\Jolt\Form\Loader');
		$loader->expects($this->once())
			->method('load')
			->will($this->returnValue(true));

		return $loader;
	}

	public function buildMockFailingWriter() {
		$writer = $this->getMockForAbstractClass('\Jolt\Form\Writer');
		$writer->expects($this->once())
			->method('write')
			->will($this->returnValue(false));

		return $writer;
	}

	public function buildMockSuccessfulWriter() {
		$writer = $this->getMockForAbstractClass('\Jolt\Form\Writer');
		$writer->expects($this->once())
			->method('write')
			->will($this->returnValue(true));

		return $writer;
	}

}
