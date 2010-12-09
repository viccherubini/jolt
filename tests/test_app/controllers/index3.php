<?php

declare(encoding='UTF-8');
use \Jolt\Controller;

require_once 'jolt/controller.php';

class Index3 extends Controller {

	public function indexAction() {
		echo 'Hi, from Jolt!', PHP_EOL;
	}

}