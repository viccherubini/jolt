<?php

declare(encoding='UTF-8');
namespace jolt_test\route\named;

use \jolt\route\named\delete,
	\jolt_test\testcase;

require_once('jolt/route/named/delete.php');

class delete_test extends testcase {

	public function testNewDeleteRoute_RequestMethodIsDelete() {
		$route = new delete('/user', 'User', 'index');
		$this->assertEquals('DELETE', $route->getRequestMethod());
	}
}