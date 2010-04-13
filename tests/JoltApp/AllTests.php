<?php

require_once 'PHPUnit/Framework.php';

class JoltApp_AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('JoltApp');
		
		return $suite;
	}

}
