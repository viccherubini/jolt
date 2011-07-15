<?php namespace jolt\route\named;
declare(encoding='UTF-8');

class delete extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('DELETE', $route, $controller, $action);
	}

}