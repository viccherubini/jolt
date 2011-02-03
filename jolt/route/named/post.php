<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

use \jolt\route\named;

class post extends named {

	public function __construct($route, $controller, $action) {
		parent::__construct('POST', $route, $controller, $action);
	}

}