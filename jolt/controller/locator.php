<?php

declare(encoding='UTF-8');
namespace jolt\controller;

class locator {

	public function __construct() {

	}

	public function __destruct() {

	}

	public function find($controller_path, $controller) {
		if (!is_file($controller_path)) {
			throw new \jolt\exception('Controller path '.$controller_path.' not found.');
		}

		require_once($controller_path);

		if (!class_exists($controller))  {
			throw new \jolt\exception('Controller class '.$controller.' does not exist.');
		}

		$controller_object = new $controller;

		if (!($controller_object instanceof \jolt\controller)) {
			throw new \jolt\exception('Controller class '.$controller.' is not an instance of \jolt\controller.');
		}

		return $controller_object;
	}

}