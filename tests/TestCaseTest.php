<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \JoltTest\TestCase;

class TestCaseTest extends TestCase {
	
	public function testBuildMockAbstractRoute_ReturnsJoltRouteObject() {
		$abstractRoute = $this->buildMockAbstractRoute();
		$this->assertTrue($abstractRoute instanceof \Jolt\Route);
	}
	
	public function testBuildMockNamedRoute_ReturnsJoltRouteNamedObject() {
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'addAction');
		$this->assertTrue($namedRoute instanceof \Jolt\Route\Named);
		$this->assertFalse($namedRoute instanceof \Jolt\Route\Restful);
	}
	
	public function testBuildMockRestfulRoute_ReturnsJoltRouteRestfulObject() {
		$restfulRoute = $this->buildMockRestfulRoute('/user', 'User');
		$this->assertTrue($restfulRoute instanceof \Jolt\Route\Restful);
		$this->assertFalse($restfulRoute instanceof \Jolt\Route\Named);
	}
	
}