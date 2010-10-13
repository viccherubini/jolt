<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Validator,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Validator.php';

class ValidatorTest extends TestCase {

	public function testTrue() {
		$this->assertTrue(true);
	}

}