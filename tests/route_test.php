<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \JoltTest\TestCase;

require_once 'jolt/route.php';

class RouteTest extends TestCase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = $this->buildMockAbstractRoute();
		$route->setRoute(NULL);
	}

}