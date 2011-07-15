<?php namespace jolt;
declare(encoding='UTF-8');

abstract class route {

	private $route = null;

	public function __construct() {

	}

	public function __destruct() {

	}

	public function get_route() {
		return $this->route;
	}

	public function set_route($route) {
		$route = trim($route);
		if (empty($route)) {
			throw new \jolt\exception('Route can not be empty.');
		}

		$this->route = $route;
		return $this;
	}

	abstract public function is_equal(\jolt\route $route);

	abstract public function is_valid();

	abstract public function is_valid_path($path);

}