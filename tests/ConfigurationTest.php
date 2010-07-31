<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Configuration,
	\JoltTest\TestCase;

require_once 'Jolt/Configuration.php';

class ConfigurationTest extends TestCase {
	
	public function test__Get_ReturnsNullOnMissingField() {
		$c = new Configuration;
		
		$this->assertTrue(is_null($c->field));
	}
	
	public function test__Set_ReturnsValueOnPresentField() {
		$name = 'vic';
		
		$c = new Configuration;
		$c->name = $name;
		
		$this->assertEquals($name, $c->name);
	}
	
}