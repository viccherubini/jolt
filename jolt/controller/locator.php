<?php

declare(encoding='UTF-8');
namespace jolt\controller;

class locator {

	private $file = NULL;
	private $path = NULL;

	const EXT = '.php';

	public function __construct() {

	}

	public function __destruct() {

	}

	public function load($path, $controller) {
		$bits = explode('\\', $controller);
		$bits_length = count($bits);

		$this->file = $bits[$bits_length-1];
		$this->file = $this->convert_underscores_to_dashes($this->file);
		$this->path = $path;

		if (0 === preg_match('/\\' . self::EXT . '$/i', $controller)) {
			$this->file .= self::EXT;
		}

		$path_length = strlen($path);
		if ($path[$path_length-1] !== DIRECTORY_SEPARATOR) {
			$this->path .= DIRECTORY_SEPARATOR;
		}

		$controller_path = $this->path . $this->file;
		if (!is_file($controller_path)) {
			throw new \jolt\exception('controller path not found');
		}

		require_once($controller_path);

		if (!class_exists($controller))  {
			throw new \jolt\exception('controller class ' . $controller . ' does not exist');
		}

		$controller_object = new $controller;

		if (!($controller_object instanceof \jolt\controller)) {
			throw new \jolt\exception('controller_locator_controller_not_instance_of_controller' . $controller);
		}

		return $controller_object;
	}

	public function get_path() {
		return $this->path;
	}

	public function get_file() {
		return $this->file;
	}

	private function convert_underscores_to_dashes($v) {
		//$v = strtolower(substr($v, 0, 1)) . substr($v, 1);
		//$v = preg_replace('/[A-Z]/', '-\\0', $v);
		//$v = strtolower($v);
		$v = strtolower($v);
		$v = str_replace('_', '-', $v);
		return $v;
	}

}