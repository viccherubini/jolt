<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\jolt,
	\jolt_test\testcase;

require_once('jolt/jolt.php');

class jolt_test extends testcase {

	public function testTrue() {
		$this->assertTrue(true);
	}

}