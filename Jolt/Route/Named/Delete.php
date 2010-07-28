<?php

declare(encoding='UTF-8');
namespace Jolt\Route\Named;

use \Jolt\Route\Named;

class Delete extends Named {

	public function __construct($route, $controller, $action) {
		parent::__construct('DELETE', $route, $controller, $action);
	}

}