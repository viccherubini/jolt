<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Jolt,
	\JoltTest\TestCase;

require_once 'Jolt/Jolt.php';

class JoltTest extends TestCase {
	
	public function testTrue() {
		$this->assertTrue(true);
	}
}