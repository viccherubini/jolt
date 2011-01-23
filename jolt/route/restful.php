<?php

declare(encoding='UTF-8');
namespace jolt\route;

use \jolt\route;

class restful extends route {

	private $resource = NULL;

	public function __construct($route, $resource) {
		$this->set_route($route)
			->set_resource($resource);
	}

	public function set_resource($resource) {
		$resource = trim($resource);
		if (empty($resource)) {
			throw new \jolt\exception('Restful route resource can not be empty.');
		}

		$this->resource = $resource;
		return $this;
	}

	public function get_resource() {
		return $this->resource;
	}

	public function is_equal(Route $route) {
		return (
			$route instanceof \jolt\route\restful &&
			$this->get_route() === $route->get_route() &&
			$this->get_resource() === $route->get_resource()
		);
	}

	public function is_valid() {
		$route = $this->get_route();

		/* Special case of a valid route. */
		if ('/' == $route) {
			return true;
		}

		if (0 === preg_match('#^/([a-z]+)([a-z0-9_\-]*)$#i', $route)) {
			return false;
		}

		return true;
	}

	public function is_valid_path($uri) {
		$route = trim($this->get_route());
		return ($route === trim($uri));
	}

}