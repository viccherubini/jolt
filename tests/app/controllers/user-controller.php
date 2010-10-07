<?php

declare(encoding='UTF-8');
use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class UserController extends Controller {
	
	public function indexAction() {
		echo 'Hi, from Jolt\\UserController!', PHP_EOL;
	}
	
}