<?php

declare(encoding='UTF-8');
namespace jolt;

class client {

	private $controller = NULL;
	private $headers = array();

	public function __construct() {

	}

	public function __destruct() {

	}

	public function __toString() {
		$output = $this->build_output();
		if (empty($output)) {
			$output = '';
		}
		return $output;
	}

	public function attach_controller(\jolt\controller $controller) {
		$this->controller = clone $controller;
		return $this;
	}

	public function build_output() {
		if (is_null($this->controller)) {
			return '';
		}

		$controller = $this->controller;

		$response_code = $controller->get_response_code();
		$content_type = $controller->get_content_type();

		$controller_headers = $controller->get_headers();

		$headers = $this->headers;
		if (0 === count($this->headers)) {
			$headers = headers_list();
		}

		// Always remove the Content-Type header, let Jolt handle it
		header_remove('Content-Type');
		header('Content-Type: '.$content_type, true, $response_code);

		if (defined('JOLT_VERSION')) {
			header('X-Framework: Jolt '.JOLT_VERSION);
		}

		$memory_usage_kb = round((memory_get_peak_usage()/(1024*400)));
		$ascii_dick = '())'.str_repeat('=', $memory_usage_kb).'D';

		header('X-ASCII-Dick: '.$ascii_dick);

		foreach ($headers as $complete_header) {
			foreach ($controller_headers as $controller_header => $controller_header_value) {
				$controller_header = trim($controller_header);
				if (false !== stripos($complete_header, $controller_header) ) {
					header_remove($controller_header);
				}

				$controller_header = strtolower($controller_header);

				// Special case for the Location header to prevent __toString() from not outputting anything.
				if ('location' === $controller_header) {
					header($controller_header.': '.$controller_header_value);
					return '';
				} else {
					header($controller_header.': '.$controller_header_value, true, $response_code);
				}
			}
		}

		$rendered_controller = $this->controller
			->get_rendered_controller();

		return $rendered_controller;
	}

	public function set_headers($headers) {
		$this->headers = $headers;
		return $this;
	}

	public function get_controller() {
		return $this->controller;
	}

}
