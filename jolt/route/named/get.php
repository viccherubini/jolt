<?php namespace jolt\route\named;
declare(encoding='UTF-8');

class get extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('GET', $route, $controller, $action);
	}

}