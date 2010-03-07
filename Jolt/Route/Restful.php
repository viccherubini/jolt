<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Route.php';

class Route_Restful extends \Jolt\Route {

	
	public function isValid() {
		return false;
	}
}