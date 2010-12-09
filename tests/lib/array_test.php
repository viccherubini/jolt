<?php

declare(encoding='UTF-8');
namespace JoltTest\Lib;

use \Jolt\Lib\array_exists_all,
	\JoltTest\TestCase;

class ArrayTest extends TestCase {

	/**
	 * @dataProvider providerArrayGetDefault
	 */
	public function testArrayGet_ReturnsDefaultValue($default) {
		$this->assertEquals($default, \Jolt\Lib\array_get('missing', array(), $default));
	}

	public function testArrayGet_FindsKey() {
		$array = array(
			1 => 'one',
			'two' => 2
		);
		
		$this->assertEquals($array[1], \Jolt\Lib\array_get(1, $array));
		$this->assertEquals($array['two'], \Jolt\Lib\array_get('two', $array));
	}

	public function testArrayKeysExist_KeyListMustBeArray() {
		$keyList = NULL;
		
		$existsAll = \Jolt\Lib\array_keys_exist($keyList, array());
		$this->assertFalse($existsAll);
	}
	
	public function testArrayKeysExist_ArrayMustBeArray() {
		$keyList = range(1, 10);
		$array = NULL;
		
		$existsAll = \Jolt\Lib\array_keys_exist($keyList, $array);
		$this->assertFalse($existsAll);
	}
	
	public function testArrayKeysExist_ReturnsTrueIfAllKeysExist() {
		$keyList = array('a', 'b', 'c');
		$array = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4);
		
		$existsAll = \Jolt\Lib\array_keys_exist($keyList, $array);
		$this->assertTrue($existsAll);
	}
	
	public function testArrayKeysExist_ValueIsNull() {
		$keyList = array('a', 'b', 'c');
		$array = array('a' => 1, 'b' => 2, 'c' => NULL, 'd' => 4);
		
		$existsAll = \Jolt\Lib\array_keys_exist($keyList, $array);
		$this->assertTrue($existsAll);
	}
	
	public function testArrayKeysExist_SingleKeyIsMissing() {
		$keyList = array('a', 'b', 'c');
		$array = array('a' => 1, 'b' => 2, 'd' => 4);
		
		$existsAll = \Jolt\Lib\array_keys_exist($keyList, $array);
		$this->assertFalse($existsAll);
	}
	
	public function testArrayEmpty_ArgumentMustBeArray() {
		$array = NULL;
		
		$empty = \Jolt\Lib\array_empty($array);
		$this->assertTrue($empty);
	}
	
	public function testArrayEmpty_EntirelyEmpty() {
		$array1 = array_fill(0, 10, NULL);
		$array2 = array_fill(0, 10, array());
		
		$empty1 = \Jolt\Lib\array_empty($array1);
		$empty2 = \Jolt\Lib\array_empty($array2);
		
		$this->assertTrue($empty1);
		$this->assertTrue($empty2);
	}
	
	public function testArrayEmpty_OneOrMoreValue() {
		$array = array_fill(0, 10, mt_rand(1, 100));
		array_push($array, NULL);
		
		$empty = \Jolt\Lib\array_empty($array);
		$this->assertFalse($empty);
	}
	
	public function providerArrayGetDefault() {
		return array(
			array('1'),
			array(1),
			array(1.09),
			array('A'),
			array(NULL),
			array(new \stdClass),
			array(array())
		);
	}
	
	public function providerArrayWithEmptyValue() {
		return array(
			array(array(1, 2, 3, NULL)),
			array(array('a' => 'b', 'a', 'd' => array()))
		);
	}
}