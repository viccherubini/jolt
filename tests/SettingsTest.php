<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Settings,
	\JoltTest\TestCase;

require_once 'Jolt/Settings.php';

class SettingsTest extends TestCase {
	
	public function test__Get_ReturnsNullOnMissingField() {
		$c = new Settings;
		
		$this->assertTrue(is_null($c->field));
	}
	
	public function test__Set_ReturnsValueOnPresentField() {
		$name = 'vic';
		
		$c = new Settings;
		$c->name = $name;

		$this->assertEquals($name, $c->name);
	}
	
}
