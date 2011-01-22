<?php

declare(encoding='UTF-8');
namespace jolt_test\route\named;

use \jolt\route\named\put,
	\jolt_test\testcase;

require_once('jolt/route/named/put.php');

class put_test extends testcase {

	public function testNewPutRoute_RequestMethodIsPut() {
		$route = new put('/user', 'User', 'index');
		$this->assertEquals('PUT', $route->getRequestMethod());
	}
}