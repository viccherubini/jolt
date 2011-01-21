<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\registry,
	\jolt_test\testcase;

require_once('jolt/registry.php');

class registry_test extends testcase {
	public function setUp() {
		registry::reset();
	}

	public function testPushingScalarElements() {
		registry::reset();

		registry::push('string', 'value');
		$this->assertEquals('value', registry::pop('string'));

		registry::push('integer', 10);
		$this->assertEquals(10, registry::pop('integer'));

		registry::push('float', 10.45);
		$this->assertEquals(10.45, registry::pop('float'));
	}

	public function testPushingComplexElements() {
		registry::reset();

		$stdClass = new \stdClass();
		$stdClass->name = 'Vic Cherubini';
		registry::push('std_class', $stdClass);
		$this->assertEquals($stdClass, registry::pop('std_class'));

		$array = array('name' => 'Vic Cherubini', 'language_list' => array('php', 'perl', 'javascript', 'c++', 'c', 'sql'));
		registry::push('array', $array);
		$this->assertEquals($array, registry::pop('array'));
	}

	public function testPoppingElements() {
		registry::reset();

		registry::push('delete_me', 'delete me please');
		$this->assertEquals('delete me please', registry::pop('delete_me', true));
		$this->assertEquals(NULL, registry::pop('delete_me'));
	}

	public function testUpdatingElements() {
		registry::reset();

		/* Disallowing overwrites. */
		registry::push('do_not_overwrite', 'dno', false);
		$this->assertEquals('dno', registry::pop('do_not_overwrite'));

		registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', registry::pop('do_not_overwrite'));

		/* Allowing overwrites. */
		registry::push('allow_overwrite', 'ao');
		$this->assertEquals('ao', registry::pop('allow_overwrite'));

		registry::push('allow_overwrite', 'ao2');
		$this->assertEquals('ao2', registry::pop('allow_overwrite'));

		/* Disallowing overwrites, but deleting the element so it can be overwritten. */
		registry::push('do_not_overwrite', 'dno', false);
		registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', registry::pop('do_not_overwrite', true));

		registry::push('do_not_overwrite', 'dno3', false);
		$this->assertEquals('dno3', registry::pop('do_not_overwrite'));
	}

}
