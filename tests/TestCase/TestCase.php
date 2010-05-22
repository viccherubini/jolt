<?php

class Jolt_TestCase_TestCaseTest extends Jolt_TestCase {
	
	public function testBuildingAbstractRouteReturnsJoltRouteObject() {
		$abstract_route = $this->buildAbstractRoute();
		$this->assertTrue($abstract_route instanceof Jolt_Route);
	}
	
	public function testBuildingNamedRouteReturnsJoltRouteNamedObject() {
		$named_route = $this->buildNamedRoute('/user', 'User', 'addAction');
		$this->assertTrue($named_route instanceof Jolt_Route_Named);
	}
	
	public function testBuildingRestfulRouteReturnsJoltRestfulNamedObject() {
		$restful_route = $this->buildRestfulRoute('/user', 'User');
		$this->assertTrue($restful_route instanceof Jolt_Route_Restful);
	}
}