<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

use \jolt\route\named;

class get extends named {

	public function __construct($route, $controller, $action) {
		parent::__construct('GET', $route, $controller, $action);
	}

}