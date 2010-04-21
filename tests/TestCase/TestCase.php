<?php

class Jolt_TestCase_TestCaseTest extends Jolt_TestCase {
	public function test_Building_Abstract_Route_Returns_Jolt_Route_Object() {
		$abstract_route = $this->buildAbstractRoute();
		$this->assertTrue($abstract_route instanceof Jolt_Route);
	}
	
	public function test_Building_Named_Route_Returns_Jolt_Route_Named_Object() {
		$named_route = $this->buildNamedRoute('/user', 'User', 'addAction');
		$this->assertTrue($named_route instanceof Jolt_Route_Named);
	}
	
	public function test_Building_Restful_Route_Returns_Jolt_Restful_Named_Object() {
		$restful_route = $this->buildRestfulRoute('/user', 'User');
		$this->assertTrue($restful_route instanceof Jolt_Route_Restful);
	}
}