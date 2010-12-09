<?php

declare(encoding='UTF-8');
namespace JoltApp;

class Broken2 {
	
	public function indexAction() {
		echo 'You should never see this because this is a broken controller', PHP_EOL;
	}
	
}