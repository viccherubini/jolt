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
		$form->setDataKey('');
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
	public function testValidate_ThrowsExceptionIfMoreFieldsThanRuleSets() {
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'count'));

		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));

		$validator->expects($this->once())
			->method('count')
			->will($this->returnValue(0));

		$form = new Form;
		$form->attachValidator($validator);
		$form->setData(array('username' => 'vmc', 'age' => 26));

		$form->validate();
	}

	/**
	 * @expectedException \JoltTest\Exception
	 */
	public function testValidate_ThrowCustomExceptionOnFailure() {
		$validator = $this->getMock('\Jolt\Form\Validator', array('isEmpty', 'count'));

		$validator->expects($this->once())
			->method('isEmpty')
			->will($this->returnValue(false));

		$validator->expects($this->once())
			->method('count')
			->will($this->returnValue(0));

		$form = new Form;
		$form->attachException(new \JoltTest\Exception);
		$form->attachValidator($validator);
		$form->setData(array('username' => 'vmc', 'age' => 26));

		$form->validate();
	}

	public function testGetDataSet_ReturnsDataFromDataKey() {
		$dataKey = 'article';
		$data = array(
			'article' => array(
				'title' => 'The article title',
				'content' => 'Content of the article is here.',
				'created' => date('Y-m-d H:i:s')
			)
		);

		$form = new Form;
		$form->setDataKey($dataKey);
		$form->setData($data);

		$this->assertEquals($data[$dataKey], $form->getDataSet());
	}

	public function testGetDataSet_ReturnsAllDataWhenNoDataKey() {
		$data = array('title' => 'the article title');

		$form = new Form;
		$form->setData($data);

		$this->assertEquals($data, $form->getDataSet());
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

		$loader = $this->getMock('\Jolt\Form\Loader\Db', array('attachPdo', 'setId', 'setName', 'getDataKey', 'getData', 'load'));

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
			->method('getDataKey')
			->will($this->returnValue('article'));

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

		$writer = $this->getMock('\Jolt\Form\Writer\Db', array('attachPdo', 'setId', 'setName', 'setDataKey', 'setData', 'write'));

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
			->method('setDataKey')
			->with($this->equalTo(''))
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

}

class Exception extends \Exception {

}