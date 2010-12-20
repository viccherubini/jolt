<?php

declare(encoding='UTF-8');
use \jolt\controller;

require_once('jolt/controller.php');

class index2 extends controller {

	public function index_action() {
		echo 'Hi, from jolt!', PHP_EOL;
	}

}