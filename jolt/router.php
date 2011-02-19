<?php

declare(encoding='UTF-8');
namespace jolt;

class router {

	private $http_404_route = NULL;
	private $path = NULL;
	private $forced_route = NULL;
	private $request_method = NULL;
	private $route_parameter = '__u';

	private $parameters = array();
	private $routes = array();

	public function __construct() {
		$this->routes = array();
		$this->set_request_method('GET');
	}

	public function __destruct() {
		unset($this->routes);
	}

	public function add_route(\jolt\route $route) {
		$route_exists = false;
		foreach ($this->routes as $k => $v) {
			if ($v->is_equal($route)) {
				$route_exists = true;
				break;
			}
		}

		if ($route_exists) {
			$route = $route->get_route();
			throw new \jolt\exception('Duplicate route '.$route.' added to Router.');
		}

		$this->routes[] = clone $route;
		return $this;
	}

	public function add_routes(array $routes) {
		if (0 == count($routes)) {
			throw new \jolt\exception('Array of Routes to be added to Router can not be empty.');
		}

		foreach ($routes as $route) {
			$this->add_route($route);
		}
		return $this;
	}

	public function force_route($route) {
		$this->forced_route = $route;
		return $this;
	}

	public function execute($path=NULL) {
		if (0 === count($this->routes)) {
			throw new \jolt\exception('No routes have been attached to the Router.');
		}

		if (is_null($path)) {
			$path = $this->extract_path();
		}

		if (is_null($this->http_404_route)) {
			throw new \jolt\exception('A 404 Route has not been attached to the Router.');
		}

		$matched_route = NULL;
		foreach ($this->routes as $route) {
			if (is_null($matched_route)) {
				$route_request_method = $route->get_request_method();
				if ($route_request_method === $this->request_method && $route->is_valid_path($path)) {
					$matched_route = clone $route;
					break;
				}
			}
		}

		if (is_null($matched_route)) {
			$matched_route = $this->http_404_route;
		}
		return $matched_route;
	}

	public function set_http_404_route(\jolt\route $route) {
		$this->http_404_route = clone $route;
		return $this;
	}

	public function set_parameters(array $parameters) {
		$this->parameters = $parameters;
		return $this;
	}

	public function set_path($path) {
		$this->path = trim($path);
		return $this;
	}

	public function set_request_method($request_method) {
		$this->request_method = strtoupper(trim($request_method));
		return $this;
	}

	public function set_route_parameter($route_parameter) {
		$this->route_parameter = $route_parameter;
		return $this;
	}

	public function get_path() {
		return $this->path;
	}

	public function get_request_method() {
		return $this->request_method;
	}

	public function get_routes() {
		return (array)$this->routes;
	}

	public function get_route_parameter() {
		return $this->route_parameter;
	}

	private function extract_path() {
		$path = NULL;

		if (!empty($this->forced_route)) {
			$path = $this->forced_route;
		} elseif (array_key_exists($this->route_parameter, $this->parameters)) {
			$path = $this->parameters[$this->route_parameter];
		}

		$this->set_path($path);
		return $path;
	}

}