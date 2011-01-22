<?php

declare(encoding='UTF-8');
namespace jolt_test\form;

require_once('form/validator_test.php');

require_once('form/loader/db_test.php');
require_once('form/writer/db_test.php');

class alltests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Form Tests');

		$suite->addTestSuite('\jolt_test\form\validator_test');

		$suite->addTestSuite('\jolt_test\form\loader\db_test');
		$suite->addTestSuite('\jolt_test\form\writer\db_test');

		return $suite;
	}
}
