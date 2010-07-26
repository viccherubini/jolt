<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'Jolt/Route.php';

class RouteTest extends TestCase {
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = $this->buildMockAbstractRoute();
		$route->setRoute(NULL);
	}
	
}