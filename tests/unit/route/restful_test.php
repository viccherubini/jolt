<?php

declare(encoding='UTF-8');
namespace jolt_test\route;

use \jolt\route\restful,
	\jolt_test\testcase;

require_once('jolt/route/restful.php');

class restful_test extends testcase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRestfulRoute_MustHaveRoute() {
		$route = new restful(NULL, 'Resource');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRestfulRoute_MustHaveResource() {
		$route = new restful('/user', NULL);
	}

	public function testGetRoute_ReturnsCorrectRoute() {
		$route = new restful('/user', 'Resource');
		$this->assertEquals('/user', $route->get_route());
	}

	public function testGetResource_ReturnsCorrectResourceName() {
		$route = new restful('/user', 'User');
		$this->assertEquals('User', $route->getResource());
	}

	public function testIsEqual_ReturnsTrueForEqualRoute() {
		$route1 = new restful('/user', 'User');
		$route2 = new restful('/user', 'User');

		$this->assertTrue($route1->is_equal($route2));
		$this->assertTrue($route2->is_equal($route1));
	}

	public function testIsEqual_ReturnsFalseForNamedRoute() {
		$restfulRoute = new restful('/user', 'User');
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');

		$this->assertFalse($restfulRoute->is_equal($namedRoute));
	}

	public function testIsEqual_ReturnsFalseForDifferentRoute() {
		$route1 = new restful('/user', 'User');
		$route2 = new restful('/usr', 'User');

		$this->assertFalse($route1->is_equal($route2));
		$this->assertFalse($route2->is_equal($route1));
	}

	public function testIsEqual_ReturnsFalseForDifferentResource() {
		$route1 = new restful('/user', 'User');
		$route2 = new restful('/user', 'Usr');

		$this->assertFalse($route1->is_equal($route2));
		$this->assertFalse($route2->is_equal($route1));
	}

	/**
	 * @dataProvider providerValidRoute
	 */
	public function testIsValid_ReturnsTrueForValidRoute($route) {
		$route = new restful($route, 'Resource');

		$this->assertTrue($route->is_valid());
	}

	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function testIsValid_ReturnsFalseForInvalidRoute($route) {
		$route = new restful($route, 'Resource');

		$this->assertFalse($route->is_valid());
	}

	/**
	 * @dataProvider providerValidRouteAndPath
	 */
	public function testIsValidPath_ReturnsTrueForValidRouteAndPath($route, $path) {
		$route = new restful($route, 'Resource');

		$this->assertTrue($route->is_valid_path($path));
	}

	/**
	 * @dataProvider providerInvalidRouteAndPath
	 */
	public function testIsValidPath_ReturnsFalseForInvalidRouteAndPath($route, $path) {
		$route = new restful($route, 'Resource');

		$this->assertFalse($route->is_valid_path($path));
	}

	public function providerValidRoute() {
		return array(
			array('/'),
			array('/abc'),
			array('/abc9'),
			array('/long_route'),
			array('/long-route'),
			array('/abc99'),
			array('/abc0'),
			array('/ABC'),
			array('/ABC0'),
			array('/ABC99'),
			array('/ABC-99'),
			array('/ABC_99')
		);
	}

	public function providerInvalidRoute() {
		return array(
			array('//'),
			array('///'),
			array('/abc/*'),
			array('/abc/*/'),
			array('/abc/*/d'),
			array('/abc/*/d/'),
			array('abc'),
			array('/abc/'),
			array('/abc9/'),
			array('/long_route/'),
			array('/long-route/'),
			array('/abc99/'),
			array('/abc0/'),
			array('/ABC/'),
			array('/ABC0/'),
			array('/ABC99/'),
			array('/ABC-99/'),
			array('/ABC_99/'),
			array('/abc/%n/'),
			array('/abc/def/efg/%n'),
			array('/abc/def/%s/%n'),
			array('/abc.def/%s/%n'),
			array('/abc/usr/%n/blah/%s'),
			array('/tutorial/%s.html'),
			array('/search/result-%n.html'),
			array('/abc./'),
			array('/abc.')
		);
	}

	public function providerValidRouteAndPath() {
		return array(
			array('/abc', '/abc'),
			array('/user', '/user'),
			array('/user-long-route', '/user-long-route'),
			array('/ABC_99', '/ABC_99')
		);
	}

	public function providerInvalidRouteAndPath() {
		return array(
			array('/abc', '/Abc'),
			array('/user', '/usr'),
			array('/user-long-route', '/user_long_route'),
			array('/ABC_99', '/ABC-99'),
			array('/ABC_99', '/abc_99')
		);

	}
}