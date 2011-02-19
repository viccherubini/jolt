<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

class put extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('PUT', $route, $controller, $action);
	}

}