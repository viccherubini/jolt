<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt_test\testcase;

require_once('jolt/route.php');

class route_test extends testcase {

	/**
	 * @expectedException \jolt\exception
	 */
	public function test_RouteCanNotBeEmpty() {
		$route = $this->getMockForAbstractClass('\jolt\route');
		$route->set_route(NULL);
	}

	public function test_RouteSet() {
		$path = '/path/to/route';

		$route = $this->getMockForAbstractClass('\jolt\route');
		$route->set_route($path);

		$this->assertEquals($path, $route->get_route());
	}

}