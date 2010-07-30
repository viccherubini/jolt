<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Registry,
	\JoltTest\TestCase;

require_once 'Jolt/Registry.php';

class RegistryTest extends TestCase {
	public function setUp() {
		Registry::reset();
	}
	
	public function testPushingScalarElements() {
		Registry::reset();
		
		Registry::push('string', 'value');
		$this->assertEquals('value', Registry::pop('string'));
		
		Registry::push('integer', 10);
		$this->assertEquals(10, Registry::pop('integer'));
		
		Registry::push('float', 10.45);
		$this->assertEquals(10.45, Registry::pop('float'));
	}
	
	public function testPushingComplexElements() {
		Registry::reset();
		
		$stdClass = new \stdClass();
		$stdClass->name = 'Vic Cherubini';
		Registry::push('std_class', $stdClass);
		$this->assertEquals($stdClass, Registry::pop('std_class'));
		
		$array = array('name' => 'Vic Cherubini', 'language_list' => array('php', 'perl', 'javascript', 'c++', 'c', 'sql'));
		Registry::push('array', $array);
		$this->assertEquals($array, Registry::pop('array'));
	}
	
	public function testPoppingElements() {
		Registry::reset();
		
		Registry::push('delete_me', 'delete me please');
		$this->assertEquals('delete me please', Registry::pop('delete_me', true));
		$this->assertEquals(NULL, Registry::pop('delete_me'));
	}
	
	public function testUpdatingElements() {
		Registry::reset();
		
		/* Disallowing overwrites. */
		Registry::push('do_not_overwrite', 'dno', false);
		$this->assertEquals('dno', Registry::pop('do_not_overwrite'));
		
		Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Registry::pop('do_not_overwrite'));
		
		/* Allowing overwrites. */
		Registry::push('allow_overwrite', 'ao');
		$this->assertEquals('ao', Registry::pop('allow_overwrite'));
		
		Registry::push('allow_overwrite', 'ao2');
		$this->assertEquals('ao2', Registry::pop('allow_overwrite'));
		
		/* Disallowing overwrites, but deleting the element so it can be overwritten. */
		Registry::push('do_not_overwrite', 'dno', false);
		Registry::push('do_not_overwrite', 'dno2');
		$this->assertEquals('dno', Registry::pop('do_not_overwrite', true));
		
		Registry::push('do_not_overwrite', 'dno3', false);
		$this->assertEquals('dno3', Registry::pop('do_not_overwrite'));
	}
	
}