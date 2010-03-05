<?php

require_once 'PHPUnit/Framework.php';

class JoltCore_TestCase extends PHPUnit_Framework_TestCase {

	/*public function testAssertIsArrayWorksForArrays() {
		$this->assertIsArray(array());
	}
	
	public function testAssertIsArrayFailsForNonArrays() {
		$this->assertIsArray('abc');
		$this->assertIsArray(10.45);
		$this->assertIsArray(new stdClass());
	}*/

	public static function assertArray($a, $message = '') {
		self::assertThat(is_array($a), self::isTrue(), $message);
	}
	
	public static function assertEmptyArray($a, $message = '') {
		self::assertArray($a);
		self::assertEquals(count($a), 0, $message);
	}
}