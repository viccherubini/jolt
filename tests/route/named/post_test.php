<?php

declare(encoding='UTF-8');
namespace jolt_test\route\named;

use \jolt\route\named\post,
	\jolt_test\testcase;

require_once('jolt/route/named/post.php');

class post_test extends testcase {

	public function testNewPostRoute_RequestMethodIsPost() {
		$route = new post('/user', 'User', 'index');
		$this->assertEquals('POST', $route->getRequestMethod());
	}
}