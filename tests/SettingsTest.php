<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Settings,
	\JoltTest\TestCase;

require_once 'Jolt/Settings.php';

class SettingsTest extends TestCase {
	
	public function test__Construct_SetsAllSettingsWithArray() {
		$settings = array(
			'name' => 'vic',
			'age' => 26
		);
		
		$s = new Settings($settings);
		
		foreach ( $settings as $k => $v ) {
			$this->assertEquals($v, $s->$k);
		}
	}
	
	public function test__Get_ReturnsNullOnMissingField() {
		$s = new Settings;
		
		$this->assertTrue(is_null($s->field));
	}
	
	public function test__Set_ReturnsValueOnPresentField() {
		$name = 'vic';
		
		$s = new Settings;
		$s->name = $name;

		$this->assertEquals($name, $s->name);
	}
	
}
