<?php

declare(encoding='UTF-8');
namespace Jolt;

class TestCaseTest extends TestCase {
	
	public function testBuildingAbstractRouteReturnsJoltRouteObject() {
		$abstract_route = $this->buildAbstractRoute();
		$this->assertTrue($abstract_route instanceof Route);
	}
	
	public function testBuildingNamedRouteReturnsJoltRouteNamedObject() {
		$named_route = $this->buildNamedRoute('/user', 'User', 'addAction');
		$this->assertTrue($named_route instanceof Route_Named);
	}
	
	public function testBuildingRestfulRouteReturnsJoltRestfulNamedObject() {
		$restful_route = $this->buildRestfulRoute('/user', 'User');
		$this->assertTrue($restful_route instanceof Route_Restful);
	}
}