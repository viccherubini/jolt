<?php

declare(encoding='UTF-8');
namespace Jolt\Route;

use \Jolt\Route\Named;

class NotFound extends Named {

	const CONTROLLER_NAME = 'Http404';

	public function __construct() {
		$this->setController(self::CONTROLLER_NAME)
			->setAction('index')
			->setArgv(array());
	}
	
}