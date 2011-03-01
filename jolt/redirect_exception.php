<?php

declare(encoding='UTF-8');
namespace jolt;

class redirect_exception extends \Exception {

	private $location = NULL;

	public function __construct($location) {
		parent::__construct('');
		$this->location = $location;
	}

	public function get_location() {
		return $this->location;
	}

}