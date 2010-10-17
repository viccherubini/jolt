<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Controller,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Controller.php';

class ControllerTest extends TestCase {

	private $controller = NULL;

	public function setUp() {
		$this->controller = $this->getMockForAbstractClass('\Jolt\Form\Controller');
	}

	public function tearDown() {
		$this->controller = NULL;
	}

	public function testSetId_IsTrimmed() {
		$id = '   id   ';
		$idTrimmed = 'id';

		$this->controller->setId($id);
		$this->assertEquals($idTrimmed, $this->controller->getId());
	}

	public function testSetName_IsTrimmed() {
		$name = '   name   ';
		$nameTrimmed = 'name';

		$this->controller->setName($name);
		$this->assertEquals($nameTrimmed, $this->controller->getName());
	}

	public function testSetDataKey_IsTrimmed() {
		$dataKey = '   dataKey   ';
		$dataKeyTrimmed = 'dataKey';

		$this->controller->setDataKey($dataKey);
		$this->assertEquals($dataKeyTrimmed, $this->controller->getDataKey());
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testSetData_MustBeArray() {
		$this->writer->setData('not an array');
	}

}