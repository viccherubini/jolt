<?php

declare(encoding='UTF-8');
namespace jolt;

class redirect_exception extends \Exception {

	private $location = NULL;

	public function __construct($message, $location) {
		parent::__construct($msg);
		$this->location = $location;
	}

	public function get_location() {
		return $location;
	}
	
}