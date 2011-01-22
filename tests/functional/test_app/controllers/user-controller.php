<?php

declare(encoding='UTF-8');
use \jolt\controller;

require_once('jolt/controller.php');

class user_controller extends controller {

	public function index_action() {
		echo 'Hi, from jolt\\user_controller!', PHP_EOL;
	}

}