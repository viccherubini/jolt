<?php

declare(encoding='UTF-8');
namespace jolt\route;

use \jolt\route;

class named extends route {

	private $action = NULL;
	private $controller = NULL;
	private $request_method = NULL;

	private $argv = array();
	private $request_methods = array();

	public function __construct($request_method, $route, $controller, $action) {
		$this->request_methods = array('GET' => true, 'POST' => true, 'PUT' => true, 'DELETE' => true);

		$this->set_request_method($request_method)
			->set_route($route)
			->set_controller($controller)
			->set_action($action)
			->set_argv(array());
	}

	public function __destruct() {
		$this->route = $this->controller = $this->action = NULL;
	}

	public function set_action($action) {
		$action = (!is_string($action) ? NULL : trim($action));
		if (empty($action)) {
			throw new \jolt\exception('Named route action can not be empty.');
		}

		$this->action = $action;
		return $this;
	}

	public function set_argv(array $argv) {
		$this->argv = $argv;
		return $this;
	}

	public function set_controller($controller) {
		$controller = (!is_string($controller) ? NULL : trim($controller));
		if (empty($controller)) {
			throw new \jolt\exception('Named route controller can not be empty.');
		}

		$this->controller = $controller;
		return $this;
	}

	public function set_request_method($request_method) {
		$request_method = (!is_string($request_method) ? NULL : strtoupper(trim($request_method)));
		if (!isset($this->request_methods[$request_method])) {
			throw new \jolt\exception("Named route request method '".$request_method."' is invalid.");
		}

		$this->request_method = $request_method;
		return $this;
	}

	public function get_action() {
		return $this->action;
	}

	public function get_argv() {
		return $this->argv;
	}

	public function get_controller() {
		return $this->controller;
	}

	public function get_request_method() {
		return $this->request_method;
	}

	public function is_equal(route $route) {
		return (
			$route instanceof \jolt\route\named &&
			$route->get_request_method() === $this->get_request_method() &&
			$route->get_route() === $this->get_route() &&
			$route->get_controller() === $this->get_controller() &&
			$route->get_action() === $this->get_action()
		);
	}

	public function is_valid() {
		$route = $this->get_route();

		/* Special case of a valid route. */
		if ('/' === $route || '/*' === $route) {
			return true;
		}

		/**
		 * Jolt is more restrictive about routes than the normal Internet RFC
		 * standards. This is to keep them clean, legible and readible.
		 *
		 * @see tests/Jolt/Route/Route/NamedTest.php
		 */
		if (0 === preg_match('#^/([a-z]+)([a-z0-9_\-/%\.\*]*)$#i', $route)) {
			return false;
		}

		return true;
	}

	public function is_valid_path($uri) {
		// Remove the beginning / from the URI and route.
		$uri = ltrim($uri, '/');
		$uri_chunks = explode('/', $uri);
		$uri_chunks_count = count($uri_chunks);

		$route = $this->get_route();
		$route = ltrim($route, '/');
		$route_chunks = explode('/', $route);
		$route_chunks_count = count($route_chunks);

		// If all of the chunks eventually match, we have a matched route.
		$matched_chunk_count = 0;

		// List of arguments to pass to the action method.
		$argv = array();

		if ($uri_chunks_count === $route_chunks_count) {
			for ($i=0; $i<$route_chunks_count; $i++) {
				$uri_chunk_value = $uri_chunks[$i];
				$route_chunk_value = $route_chunks[$i];

				if ($uri_chunk_value == $route_chunk_value) {
					// If the two are exactly the same, no expansion is needed.
					$matched_chunk_count++;
				} else {
					$offset = stripos($route_chunk_value, '%');
					if (false !== $offset && true === isset($route_chunk_value[$offset+1])) {
						$route_chunk_value_type = $route_chunk_value[$offset+1];
						$route_chunk_value_length = strlen($route_chunk_value);

						if (0 !== $offset) {
							$uri_chunk_value = trim(substr_replace($uri_chunk_value, NULL, 0, $offset));
						}

						if (($offset+2) < $route_chunk_value_length) {
							$goto = strlen(substr($route_chunk_value, $offset+2));
							$uri_chunk_value = substr_replace($uri_chunk_value, NULL, -$goto);
						}

						// Now that we have the correct $uri_chunk_value values, let's make sure their types are correct.
						$matched = false;

						switch ($route_chunk_value_type) {
							case 'n': {
								$matched = is_numeric($uri_chunk_value);
								break;
							}

							case 's': {
								$matched = is_string($uri_chunk_value) && !is_numeric($uri_chunk_value);
								break;
							}
						}

						if (true === $matched) {
							$matched_chunk_count++;
							$argv[] = $uri_chunk_value;
						}
					}
				}
			}
		}

		$this->argv = $argv;
		return ($matched_chunk_count === $route_chunks_count);
	}

}