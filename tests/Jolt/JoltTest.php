<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Jolt.php';

class JoltTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testAttachedRouterMustHaveAtLeastOneRoute() {
		$router = $this->buildMockEmptyRouter();
		Jolt::attachRouter($router);
	}
}