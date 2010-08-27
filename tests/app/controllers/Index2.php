<?php

declare(encoding='UTF-8');
use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class Index2 extends Controller {
	
	public function indexAction() {
		echo 'Hi, from Jolt!', PHP_EOL;
	}
	
}