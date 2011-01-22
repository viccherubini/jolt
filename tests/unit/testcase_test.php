<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\settings,
	\jolt_test\testcase;

class testcase_test extends testcase {

	public function test_AssertTrue() {
		$this->assertTrue(true);
	}

}