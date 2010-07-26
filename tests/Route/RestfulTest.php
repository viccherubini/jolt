<?php

declare(encoding='UTF-8');
namespace JoltTest\Route;

use \Jolt\Route\Restful, \JoltTest\TestCase;

require_once 'Jolt/Route.php';
require_once 'Jolt/Route/Restful.php';

class RestfulTest extends TestCase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteCanNotBeEmpty() {
		$route = new Restful(NULL, 'Resource');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRouteMustHaveResource() {
		$route = new Restful('/user', NULL);
	}

	public function testRouteIsSetCorrectly() {
		$route = new Restful('/user', 'Resource');
		$this->assertEquals('/user', $route->getRoute());
	}
	
	public function testResourceIsSetCorrectly() {
		$route = new Restful('/user', 'Resource');
		$this->assertEquals('Resource', $route->getResource());
	}
	
}