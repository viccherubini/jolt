<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

use \jolt\route\named;

class put extends named {

	public function __construct($route, $controller, $action) {
		parent::__construct('PUT', $route, $controller, $action);
	}

}