<?php

declare(encoding='UTF-8');
namespace JoltTest\Form\Validator;

use \Jolt\Form\Validator\RuleSet,
	\JoltTest\TestCase;

require_once 'Jolt/Form/Validator/RuleSet.php';

class RuleSetTest extends TestCase {
	
	public function testTrue() {
		$this->assertTrue(true);
	}
}