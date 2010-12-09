<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\Client,
	\JoltTest\TestCase;

require_once 'jolt/client.php';
require_once 'jolt/controller.php';

class ClientTest extends TestCase {

	private $client = NULL;

	public function setUp() {
		$this->client = $this->getMock('\Jolt\Client', array('sendHeader'));
		$this->client->expects($this->any())
			->method('sendHeader')
			->will($this->returnValue(true));
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider providerInvalidJoltController
	 */
	public function testAttachController_IsJoltController($controller) {
		$client = new Client;
		$client->attachController($controller);
	}

	public function testAttachController_IsAttached() {
		$actualController = $this->buildMockController();

		$client = new Client;
		$client->attachController($actualController);
		$expectedController = $client->getController();

		$this->assertTrue($expectedController instanceof $actualController);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function _testSendHeader_SendsHeader() {
		$client = new Client;

		// PHPUnit sends a header for some reason and thus, we expect an error here.
		$client->sendHeader('X-Fake-Header', 'abc');
	}

	public function test__ToString_ControllerAttached() {
		$client = new Client;

		ob_start();
			echo $client;
		$output = ob_get_clean();

		$this->assertEmpty($output);
	}

	public function test__ToString_SendsHeaders() {
		$controller = $this->buildMockController();
		$controller->addHeader('X-Powered-By', 'Jolt/PHP');

		$this->client->attachController($controller);

		$output = $this->client->buildOutput();
		$this->assertEmpty($output);
	}

	public function test__ToString_SendsNonSentHeaders() {
		$controller = $this->buildMockController();
		$controller->addHeader('X-Powered-By', 'Jolt/PHP');

		$this->client->attachController($controller);

		$output = $this->client->buildOutput();
		$this->assertEmpty($output);
	}

	public function providerInvalidJoltController() {
		return array(
			array('a'),
			array(10),
			array(array('a')),
			array(new \stdClass),
			array(new Client)
		);
	}
}