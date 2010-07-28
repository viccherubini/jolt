<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Dispatcher;

require_once 'Jolt/Dispatcher.php';

class DispatcherTest extends TestCase {
	
	public function testTrue() {
		$dispatcher = new Dispatcher;
		$this->assertTrue(true);
		
	}
	
}