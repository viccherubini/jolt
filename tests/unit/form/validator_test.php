<?php

declare(encoding='UTF-8');
namespace jolt_test\form;

use \jolt\form\validator,
	\jolt_test\testcase;

require_once('jolt/form/validator.php');

class validator_test extends testcase {


	public function testIsEmpty_EmptyWhenNoRuleSets() {
		$validator = new validator;
		$this->assertTrue($validator->is_empty());
	}


}
