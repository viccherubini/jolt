<?php

declare(encoding='UTF-8');
namespace JoltTest\Controller;

use \Jolt\Controller\Locator,
	\JoltTest\TestCase;

require_once 'Jolt/Controller/Locator.php';

class LocatorTest extends TestCase {

	public function testGet_AppendsExt() {
		Locator::load('/path/to/controllers', 'controller');
		$this->assertEquals('controller' . Locator::$ext, Locator::$file);
		
	}

}