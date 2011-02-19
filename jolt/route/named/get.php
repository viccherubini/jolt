<?php

declare(encoding='UTF-8');
namespace jolt\route\named;

class get extends \jolt\route\named {

	public function __construct($route, $controller, $action) {
		parent::__construct('GET', $route, $controller, $action);
	}

}