<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

class delete extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('DELETE', $route, $controller, $action);
	}

}