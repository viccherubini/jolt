<?php

declare(encoding='UTF-8');
namespace JoltTest\Form;

use \Jolt\Form\Validator,
	\JoltTest\TestCase;

require_once 'jolt/form/validator.php';

class ValidatorTest extends TestCase {


	public function testIsEmpty_EmptyWhenNoRuleSets() {
		$validator = new Validator;
		$this->assertTrue($validator->isEmpty());
	}


}