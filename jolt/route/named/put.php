<?php namespace jolt\route\named;
declare(encoding='UTF-8');

class put extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('PUT', $route, $controller, $action);
	}

}