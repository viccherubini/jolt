<?php

declare(encoding='UTF-8');
namespace jolt_test;

use \jolt\client,
	\jolt_test\testcase;

require_once(__DIR__.'/../../jolt/client.php');
require_once(__DIR__.'/testcase.php');

class client_test extends \PHPUnit_Framework_TestCase {

	private $controller = NULL;
	private $output = '';

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider provider_invalid_controller
	 */
	public function _test_attach_controller__requires_jolt_controller($controller) {
		$client = new client;
		$client->attach_controller($controller);
	}

	public function test_attach_controller__is_attached() {
		$actual_controller = $this->getMock('\jolt\controller');

		$client = new client;
		$client->attach_controller($actual_controller);
		$expected_controller = $client->get_controller();

		$this->assertTrue($expected_controller instanceof $actual_controller);
	}

	public function _test___toString__is_empty_without_controller_attached() {
		$client = new client;

		ob_start();
			echo $client;
		$output = ob_get_clean();

		$this->assertEmpty($output);
	}

	public function _test_build_output__sends_headers() {
		$output = 'fin jolt controller';

		$controller = $this->getMock('\jolt\controller', array('get_response_code', 'get_content_type', 'get_headers', 'get_rendered_controller'));
		$controller->expects($this->once())
			->method('get_response_code')
			->will($this->returnValue(200));

		$controller->expects($this->once())
			->method('get_content_type')
			->will($this->returnValue('text/html'));

		$controller->expects($this->once())
			->method('get_headers')
			->will($this->returnValue(array('x-fake-header' => 'jolt rocks')));

		$controller->expects($this->once())
			->method('get_rendered_controller')
			->will($this->returnValue($output));

		$client = new client;
		$client->attach_controller($controller);

		$this->assertEquals($output, $client->build_output());
	}

	public function _test_build_output__removes_overridden_headers() {
		$output = 'fin jolt controller';

		$controller = $this->getMock('\jolt\controller', array('get_response_code', 'get_content_type', 'get_headers', 'get_rendered_controller'));
		$controller->expects($this->once())
			->method('get_response_code')
			->will($this->returnValue(200));

		$controller->expects($this->once())
			->method('get_content_type')
			->will($this->returnValue('text/html'));

		$controller->expects($this->once())
			->method('get_headers')
			->will($this->returnValue(array('x-fake-header' => 'jolt rocks')));

		$controller->expects($this->once())
			->method('get_rendered_controller')
			->will($this->returnValue($output));

		$client = new client;
		$client->attach_controller($controller);
		$client->set_headers(array('x-fake-header: jolt is pretty cool'));

		$this->assertEquals($output, $client->build_output());
	}

	public function _test_build_output__returns_empty_string_for_location_header() {
		$output = 'fin jolt controller';

		$controller = $this->getMock('\jolt\controller', array('get_response_code', 'get_content_type', 'get_headers'));
		$controller->expects($this->once())
			->method('get_response_code')
			->will($this->returnValue(200));

		$controller->expects($this->once())
			->method('get_content_type')
			->will($this->returnValue('text/html'));

		$controller->expects($this->once())
			->method('get_headers')
			->will($this->returnValue(array('location' => 'http://joltcore.org')));

		$client = new client;
		$client->attach_controller($controller);
		$client->set_headers(array('x-fake-header: jolt rocks'));

		$this->assertEmpty($client->build_output());
	}

	public function provider_invalid_controller() {
		return array(
			array('a'),
			array(10),
			array(array('a')),
			array(new \stdClass),
			array(new \jolt\client)
		);
	}

}