<?php

declare(encoding='UTF-8');
namespace Jolt\Route\Named;

use \Jolt\Route\Named;

class Put extends Named {

	public function __construct($route, $controller, $action) {
		parent::__construct('PUT', $route, $controller, $action);
	}

}