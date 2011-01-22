<?php

declare(encoding='UTF-8');
namespace jolt_test\controller;

use \jolt\controller\locator,
	\jolt_test\testcase;

require_once('vfsStream/vfsStream.php');

class locator_test extends testcase {

	private $controller = 'app_controller';
	private $controller_directory = 'jolt_app';

	public function setUp() {
		\vfsStreamWrapper::register();
	}

	/**
	 * @expectedException \jolt\exception
	 */
	public function test_find__controller_file_exists() {
		$controller_file = $this->get_controller_file();
		
		$locator = new locator;
		$locator->find(\vfsStream::url($this->controller_directory.DIRECTORY_SEPARATOR.$controller_file), $this->controller);
	}

	/**
	 * @expectedException \jolt\exception
	 */
	public function test_find__controller_class_exists() {
		$controller_file = $this->get_controller_file();
		\vfsStreamWrapper::setRoot(new \vfsStreamDirectory($this->controller_directory));

		$app_directory = \vfsStreamWrapper::getRoot();
		$app_directory->addChild(\vfsStream::newFile($controller_file)->withContent('<?php class abc { }'));

		$stream_url = \vfsStream::url($this->controller_directory.DIRECTORY_SEPARATOR.$controller_file);

		$locator = new locator;
		$locator->find($stream_url, $this->controller);
	}

	/**
	 * @expectedException \jolt\exception
	 */
	public function test_find__controller_extends_jolt_controller() {
		$controller_file = $this->get_controller_file();
		\vfsStreamWrapper::setRoot(new \vfsStreamDirectory($this->controller_directory));

		$app_directory = \vfsStreamWrapper::getRoot();
		$app_directory->addChild(\vfsStream::newFile($controller_file)->withContent('<?php class '.$this->controller.' { }'));

		$stream_url = \vfsStream::url($this->controller_directory.DIRECTORY_SEPARATOR.$controller_file);

		$locator = new locator;
		$locator->find($stream_url, $this->controller);
	}

	public function test_find__controller_object_built() {
		$controller_file = $this->get_controller_file();
		
		\vfsStreamWrapper::setRoot(new \vfsStreamDirectory($this->controller_directory));

		$app_directory = \vfsStreamWrapper::getRoot();
		$app_directory->addChild(\vfsStream::newFile($controller_file)->withContent('<?php namespace jolt_app; class '.$this->controller.' extends \jolt\controller { }'));

		$stream_url = \vfsStream::url($this->controller_directory.DIRECTORY_SEPARATOR.$controller_file);
		
		$locator = new locator;
		$app_controller = $locator->find($stream_url, '\jolt_app\\'.$this->controller);

		$this->assertTrue(is_object($app_controller));
		$this->assertTrue($app_controller instanceof \jolt\controller);
	}


	private function get_controller_file() {
		return uniqid(true).'_app_controller.php';
	}
	
}