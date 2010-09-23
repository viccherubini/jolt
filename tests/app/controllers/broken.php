<?php

declare(encoding='UTF-8');
namespace JoltApp;

use \Jolt\Controller;

require_once 'Jolt/Controller.php';

class BrokenController extends Controller {
	
	public function indexAction() {
		echo 'You should never see this because this is a broken controller', PHP_EOL;
	}
	
}