<?php namespace jolt;
declare(encoding='UTF-8');

class redirect_exception extends \Exception {

	private $location = null;

	public function __construct($location) {
		parent::__construct('');
		$this->location = $location;
	}

	public function get_location() {
		return $this->location;
	}

}