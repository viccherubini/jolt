<?php namespace jolt\route\named;
declare(encoding='UTF-8');

class post extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('POST', $route, $controller, $action);
	}

}