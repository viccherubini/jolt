<?php

declare(encoding='UTF-8');
namespace JoltTest\Route\Named;

use \Jolt\Route\Named\Post,
	\JoltTest\TestCase;

require_once 'jolt/route/named/post.php';

class PostTest extends TestCase {

	public function testNewPostRoute_RequestMethodIsPost() {
		$route = new Post('/user', 'User', 'index');
		$this->assertEquals('POST', $route->getRequestMethod());
	}
}