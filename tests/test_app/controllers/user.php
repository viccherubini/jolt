<?php

declare(encoding='UTF-8');
namespace JoltApp\User;

use \Jolt\Controller;

require_once 'jolt/controller.php';

class User extends Controller {

	public function indexAction() {
		echo 'Hi, from Jolt\\User!', PHP_EOL;
	}

}