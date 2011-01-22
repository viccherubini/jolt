<?php

declare(encoding='UTF-8');
namespace jolt_test\route\named;

use \jolt\route\named\get,
	\jolt_test\testcase;

require_once('jolt/route/named/get.php');

class get_test extends testcase {

	public function testNewGetRoute_RequestMethodIsGet() {
		$route = new get('/user', 'User', 'index');
		$this->assertEquals('GET', $route->getRequestMethod());
	}
}