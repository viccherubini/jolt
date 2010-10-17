<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Writer,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Writer.php';

class WriterTest extends TestCase {

	private $writer = NULL;

	public function setUp() {
		$this->writer = $this->getMockForAbstractClass('\Jolt\Form\Writer');
	}

	public function tearDown() {
		$this->writer = NULL;
	}

	public function testReset_DataIsEmpty() {
		$data = array('a' => 'b', 'c' => 'd');

		$this->writer->setData($data);
		$this->writer->setDataKey('a');

		$this->assertGreaterThan(1, count($this->writer->getData()));

		$this->writer->reset();

		$this->assertEmpty($this->writer->getData());
		$this->assertNull($this->writer->getDataKey());
	}

	public function testSetId_IsTrimmed() {
		$id = '   id   ';
		$idTrimmed = 'id';

		$this->writer->setId($id);
		$this->assertEquals($idTrimmed, $this->writer->getId());
	}

	public function testSetName_IsTrimmed() {
		$name = '   name   ';
		$nameTrimmed = 'name';

		$this->writer->setName($name);
		$this->assertEquals($nameTrimmed, $this->writer->getName());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetData_MustBeArray() {
		$this->writer->setData('not an array');
	}
}