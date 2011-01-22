<?php

declare(encoding='UTF-8');
namespace jolt_app;

use \jolt\controller;

require_once('jolt/controller.php');

class broken_controller extends controller {

	public function index_action() {
		echo 'You should never see this because this is a broken controller', PHP_EOL;
	}

}