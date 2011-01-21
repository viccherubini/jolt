<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\settings,
	\jolt_test\testcase;

require_once('jolt/settings.php');

class settings_text extends testcase {

	public function test__Construct_SetsAllSettingsWithArray() {
		$settings = array('name' => 'vic', 'age' => 26);

		$s = new settings($settings);

		foreach ($settings as $k => $v) {
			$this->assertEquals($v, $s->$k);
		}
	}

	public function test__Get_ReturnsNullOnMissingField() {
		$s = new settings;

		$this->assertTrue(is_null($s->field));
	}

	public function test__Set_ReturnsValueOnPresentField() {
		$name = 'vic';

		$s = new settings;
		$s->name = $name;

		$this->assertEquals($name, $s->name);
	}

	public function test_Length_ReturnsNumberOfElements() {
		$s = new settings;
		$this->assertEquals(0, $s->length());

		$s->a = 'a';
		$s->b = 'b';
		$this->assertEquals(2, $s->length());

		$s->a = 'a1'; // steaksauce
		$this->assertEquals(2, $s->length());
	}

}