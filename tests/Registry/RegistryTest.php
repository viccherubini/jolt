<?php

require_once 'Jolt/Registry.php';

class Jolt_Registry_RegistryTest extends Jolt_TestCase {
	public function setUp() {
		Jolt_Registry::reset();
	}
	
	public function testScalarElements() {
		Jolt_Registry::reset();
		
		Jolt_Registry::push('string', 'value');
		$this->assertEquals('value', Jolt_Registry::pop('string'));
		
		Jolt_Registry::push('integer', 10);
		$this->assertEquals(10, Jolt_Registry::pop('integer'));
		
		Jolt_Registry::push('float', 10.45);
		$this->assertEquals(10.45, Jolt_Registry::pop('float'));
	}
	
	public function testComplexElements() {
		Jolt_Registry::reset();
		
		$std_class = new stdClass();
		$std_class->name = 'Vic Cherubini';
		Jolt_Registry::push('std_class', $std_class);
		$this->assertEquals($std_class, Jolt_Registry::pop('std_class'));
		
		$array = array('name' => 'Vic Cherubini', 'language_list' => array('php', 'perl', 'javascript', 'c++', 'c', 'sql'));
		Jolt_Registry::push('array', $array);
		$this->assertEquals($array, Jolt_Registry::pop('array'));
	}
	
	public function testDeletingElements() {
		Jolt_Registry::reset();
		
		Jolt_Registry::push('delete_me', 'delete me please');
		$this->assertEquals('delete me please', Jolt_Registry::pop('delete_me', true));
		$this->assertEquals(NULL, Jolt_Registry::pop('delete_me'));
	}
	
	public function testOverwritingElements() {
		Jolt_Registry::reset();
		
		/* Disallowing overwrites. */
		Jolt_Registry::push('do_not_overwrite', 'dno', false);
		$this->assertEquals('dno', Jolt_Registry::pop('do_not_overwrite'));
		
		Jolt_Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Jolt_Registry::pop('do_not_overwrite'));
		
		/* Allowing overwrites. */
		Jolt_Registry::push('allow_overwrite', 'ao');
		$this->assertEquals('ao', Jolt_Registry::pop('allow_overwrite'));
		
		Jolt_Registry::push('allow_overwrite', 'ao2');
		$this->assertEquals('ao2', Jolt_Registry::pop('allow_overwrite'));
		
		/* Disallowing overwrites, but deleting the element so it can be overwritten. */
		Jolt_Registry::push('do_not_overwrite', 'dno', false);
		Jolt_Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Jolt_Registry::pop('do_not_overwrite', true));
		
		Jolt_Registry::push('do_not_overwrite', 'dno3', false);
		$this->assertEquals('dno3', Jolt_Registry::pop('do_not_overwrite'));
	}
}