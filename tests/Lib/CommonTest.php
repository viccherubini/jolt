<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

use \Jolt\Lib,
	\JoltTest\TestCase;

class CommonTest extends TestCase {

	public function testExs_PropertyExistsOnObject() {
		$object = new \stdClass;
		$object->id = mt_rand(1, 100);
		
		$exists = \Jolt\Lib\exs('id', $object);
		$this->assertTrue($exists);
	}
	
	public function testExs_PropertyMustExistOnObject() {
		$object = new \stdClass;
		
		$exists = \Jolt\Lib\exs('id', $object);
		$this->assertFalse($exists);
	}
	
	public function testExs_KeyExistsInArray() {
		$hash = array('id' => mt_rand(1, 100));
		
		$exists = \Jolt\Lib\exs('id', $hash);
		$this->assertTrue($exists);
	}
	
	public function testExs_KeyMustExistInArray() {
		$hash = array();
		
		$exists = \Jolt\Lib\exs('id', $hash);
		$this->assertFalse($exists);
	}
	
	public function testExs_MustUseObjectOrArray() {
		$object = 'not-object';
		$hash = 11;
		
		$exists1 = \Jolt\Lib\exs('k', $object);
		$exists2 = \Jolt\Lib\exs('k', $hash);
		
		$this->assertFalse($exists1);
		$this->assertFalse($exists2);
	}
	
	public function testEr_ValueReturnsFromPropertyOnObject() {
		$object = new \stdClass;
		$object->id = mt_rand(1, 100);
		
		$id = \Jolt\Lib\er('id', $object);
		
		$this->assertEquals($object->id, $id);
	}
	
	public function testEr_ValueReturnsFromKeyOnArray() {
		$hash = array('id' => mt_rand(1, 100));
		
		$id = \Jolt\Lib\er('id', $hash);
		
		$this->assertEquals($hash['id'], $id);
	}
	
	public function testEr_DefaultValueReturned() {
		$default = 10;
		$value = \Jolt\Lib\er('id', array(), $default);
		
		$this->assertEquals($default, $value);
	}
	
	public function testLibNow_ReturnsSqlDateFormat() {
		$format = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/i';
		$date = \Jolt\Lib\lib_now();
		
		$matched = preg_match($format, $date);
		$this->assertEquals(1, $matched);
	}
	
	public function testLibPeakMemory_IsFloat() {
		$peakMemory = \Jolt\Lib\lib_peak_memory();
		$this->assertTrue(is_float($peakMemory));
	}
}