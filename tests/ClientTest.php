<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Client,
	\JoltTest\TestCase;

require_once 'Jolt/Client.php';

class ClientTest extends TestCase {

	public function testTrue() {
		$this->assertTrue(true);
	}
}