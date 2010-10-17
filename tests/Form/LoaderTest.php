<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Writer,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Loader.php';

class LoaderTest extends TestCase {

	private $loader = NULL;

	public function setUp() {
		$this->loader = $this->getMockForAbstractClass('\Jolt\Form\Loader');
	}

	public function tearDown() {
		$this->loader = NULL;
	}

	public function testSetId_IsTrimmed() {
		$id = '   id   ';
		$idTrimmed = 'id';

		$this->loader->setId($id);
		$this->assertEquals($idTrimmed, $this->loader->getId());
	}

	public function testSetName_IsTrimmed() {
		$name = '   name   ';
		$nameTrimmed = 'name';

		$this->loader->setName($name);
		$this->assertEquals($nameTrimmed, $this->loader->getName());
	}

}