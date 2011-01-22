<?php

declare(encoding='UTF-8');
namespace jolt_test\route\named;

require_once('jolt/route/named.php');

require_once('delete_test.php');
require_once('get_test.php');
require_once('post_test.php');
require_once('put_test.php');

class alltests {

	public static function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Jolt Route Named Tests');

		$suite->addTestSuite('\jolt_test\route\named\delete_test');
		$suite->addTestSuite('\jolt_test\route\named\get_test');
		$suite->addTestSuite('\jolt_test\route\named\post_test');
		$suite->addTestSuite('\jolt_test\route\named\put_test');

		return $suite;
	}
}