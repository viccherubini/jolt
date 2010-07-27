<?php

declare(encoding='UTF-8');
namespace JoltTest\Route;

use \Jolt\Route\Restful,
	\JoltTest\TestCase;

require_once 'Jolt/Route/Restful.php';

class RestfulTest extends TestCase {

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRestfulRoute_MustHaveRoute() {
		$route = new Restful(NULL, 'Resource');
	}
	
	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testNewRestfulRoute_MustHaveResource() {
		$route = new Restful('/user', NULL);
	}

	public function testGetRoute_ReturnsCorrectRoute() {
		$route = new Restful('/user', 'Resource');
		$this->assertEquals('/user', $route->getRoute());
	}
	
	public function testGetResource_ReturnsCorrectResourceName() {
		$route = new Restful('/user', 'User');
		$this->assertEquals('User', $route->getResource());
	}
	
	public function testIsEqual_ReturnsTrueForEqualRoute() {
		$route1 = new Restful('/user', 'User');
		$route2 = new Restful('/user', 'User');
		
		$this->assertTrue($route1->isEqual($route2));
		$this->assertTrue($route2->isEqual($route1));
	}
	
	public function testIsEqual_ReturnsFalseForNamedRoute() {
		$restfulRoute = new Restful('/user', 'User');
		$namedRoute = $this->buildMockNamedRoute('GET', '/user', 'User', 'index');
		
		$this->assertFalse($restfulRoute->isEqual($namedRoute));
	}
	
	public function testIsEqual_ReturnsFalseForDifferentRoute() {
		$route1 = new Restful('/user', 'User');
		$route2 = new Restful('/usr', 'User');
		
		$this->assertFalse($route1->isEqual($route2));
		$this->assertFalse($route2->isEqual($route1));
	}
	
	public function testIsEqual_ReturnsFalseForDifferentResource() {
		$route1 = new Restful('/user', 'User');
		$route2 = new Restful('/user', 'Usr');
		
		$this->assertFalse($route1->isEqual($route2));
		$this->assertFalse($route2->isEqual($route1));
	}
	
	/**
	 * @dataProvider providerValidRoute
	 */
	public function testIsValid_ReturnsTrueForValidRoute($route) {
		$route = new Restful($route, 'Resource');
		
		$this->assertTrue($route->isValid());
	}
	
	/**
	 * @dataProvider providerInvalidRoute
	 */
	public function testIsValid_ReturnsFalseForInvalidRoute($route) {
		$route = new Restful($route, 'Resource');
		
		$this->assertFalse($route->isValid());
	}
	
	/**
	 * @dataProvider providerValidRouteAndPath
	 */
	public function testIsValidPath_ReturnsTrueForValidRouteAndPath($route, $path) {
		$route = new Restful($route, 'Resource');
		
		$this->assertTrue($route->isValidPath($path));
	}
	
	/**
	 * @dataProvider providerInvalidRouteAndPath
	 */
	public function testIsValidPath_ReturnsFalseForInvalidRouteAndPath($route, $path) {
		$route = new Restful($route, 'Resource');
		
		$this->assertFalse($route->isValidPath($path));
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