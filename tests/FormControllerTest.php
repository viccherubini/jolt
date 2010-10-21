<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\FormController,
	\JoltTest\TestCase;

require_once 'Jolt/FormController.php';

class FormControllerTest extends TestCase {

	private $formController = NULL;

	public function setUp() {
		$this->formController = $this->getMockForAbstractClass('\Jolt\FormController');
	}

	public function tearDown() {
		$this->formController = NULL;
	}

	public function testSetId_IsTrimmed() {
		$id = '   id   ';
		$idTrimmed = 'id';

		$this->formController->setId($id);
		$this->assertEquals($idTrimmed, $this->formController->getId());
	}

	public function testSetName_IsTrimmed() {
		$name = '   name   ';
		$nameTrimmed = 'name';

		$this->formController->setName($name);
		$this->assertEquals($nameTrimmed, $this->formController->getName());
	}

	public function testSetDataKey_IsTrimmed() {
		$dataKey = '   dataKey   ';
		$dataKeyTrimmed = 'dataKey';

		$this->formController->setDataKey($dataKey);
		$this->assertEquals($dataKeyTrimmed, $this->formController->getDataKey());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetData_MustBeArray() {
		$this->formController->setData('not an array');
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetErrors_MustBeArray() {
		$this->formController->setErrors('not an array');
	}

	public function testGetDataCount_ReturnsNumberOfElementsInData() {
		$data = array('a' => 'first', 'b' => 'second', 'c' => 'third');
		$count = 3;

		$this->formController->setData($data);
		$this->assertEquals($count, $this->formController->getDataCount());
	}
}