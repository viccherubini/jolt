<?php

declare(encoding='UTF-8');
namespace Jolt\Route\Named;

use \Jolt\Route\Named;

class Post extends Named {

	public function __construct($route, $controller, $action) {
		parent::__construct('POST', $route, $controller, $action);
	}

}