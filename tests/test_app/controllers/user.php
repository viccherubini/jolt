<?php

declare(encoding='UTF-8');
namespace jolt_app\user;

use \jolt\controller;

require_once('jolt/controller.php');

class user extends controller {

	public function index_action() {
		echo 'Hi, from jolt\\user!', PHP_EOL;
	}

}