<?php

declare(encoding='UTF-8');
namespace Jolt\Route;

require_once 'Jolt/Route.php';

class Restful extends \Jolt\Route {

	
	public function isValid() {
		return false;
	}
}