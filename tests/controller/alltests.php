<?php

declare(encoding='UTF-8');
namespace jolt_test\controller;

require_once('controller/locator_test.php');

class alltests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('jolt_controller_locator_tests');
		$suite->addTestSuite('\jolt_test\controller\locator_test');

		return $suite;
	}

}