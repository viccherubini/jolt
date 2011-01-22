<?php

declare(encoding='UTF-8');
namespace jolt_test\controller;

use \jolt\controller\locator,
	\jolt_test\testcase;

require_once('vfsStream/vfsStream.php');

class locator_test extends testcase {

	private $app_directory = NULL;
	private $stream_url = NULL;

	private $jolt_application_directory = NULL;
	private $controller_name = NULL;
	private $controller_file = NULL;
	private $controller_file_content = NULL;

	public function setUp() {
		$this->jolt_application_directory = 'jolt_app';
		$this->controller_name = 'app_controller';
		$this->controller_file = 'app_controller.php';
		$this->controller_file_content = '<?php namespace jolt_app; class '.$this->controller_name.' extends \jolt\controller { }';

		\vfsStreamWrapper::register();
	}

	/**
	 * @expectedException \jolt\exception
	 */
	public function _test_find__controller_file_exists() {
		$locator = new locator;
		$locator->find(\vfsStream::url('jolt_app_missing'.DIRECTORY_SEPARATOR.$this->controller_file), $this->controller_name);
	}



	/**
	 * @_expectedException \jolt\exception
	 */
	public function test_find__controller_class_exists() {
		\vfsStreamWrapper::setRoot(new \vfsStreamDirectory('jolt_app'));

		$app_directory = \vfsStreamWrapper::getRoot();
		$app_directory->addChild(\vfsStream::newFile('app_controller.php')->withContent('<?php class abc { } ?>'));

		$locator = new locator;
		$locator->find(\vfsStream::url('jolt_app'.DIRECTORY_SEPARATOR.'app_controller.php'), 'app_controller');
	}

	/**
	 * @_expectedException \jolt\exception
	 */
	public function test_find__controller_extends_jolt_controller() {
		\vfsStreamWrapper::setRoot(new \vfsStreamDirectory('jolt_app'));

		$app_directory = \vfsStreamWrapper::getRoot();
		$app_directory->addChild(\vfsStream::newFile('app_controller2.php')->withContent('<?php class def { } ?>'));

		$locator = new locator;
		$locator->find(\vfsStream::url('jolt_app'.DIRECTORY_SEPARATOR.'app_controller2.php'), 'app_controller');
	}




	public function _test_find__controller_object_built() {
		//$locator = new locator;
		//$locator->find($this->stream_url, $this->controller_name);
		//var_dump(file_get_contents($this->stream_url));

	}


}