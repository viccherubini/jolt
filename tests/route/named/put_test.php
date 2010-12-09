<?php

declare(encoding='UTF-8');
namespace JoltTest\Route\Named;

use \Jolt\Route\Named\Put,
	\JoltTest\TestCase;

require_once 'jolt/route/named/put.php';

class PutTest extends TestCase {

	public function testNewPutRoute_RequestMethodIsPut() {
		$route = new Put('/user', 'User', 'index');
		$this->assertEquals('PUT', $route->getRequestMethod());
	}
}