<?php namespace jolt;
declare(encoding='UTF-8');

class controller {

	private $action = null;
	private $content_type = 'text/html';
	private $http_accept_type = 'text/html';
	private $rendered_controller = null;
	private $rendered_view = null;
	private $response_code = 200;
	private $route = null;
	private $view = null;

	private $blocks = array();
	private $headers = array();
	private $input_params = array();

	const EXT = '.php';
	const VIEWEXT = '.phtml';

	public function __construct() {

	}

	public function __destruct() {

	}

	public function __set($k, $v) {
		if ($this->has_view()) {
			$this->view->$k = $v;
		}
		return true;
	}

	public function __get($k) {
		if ($this->has_view()) {
			return $this->view->$k;
		}
		return null;
	}

	public function add_block($block_name, $block) {
		if ($this->has_view()) {
			$this->view->add_block($block_name, $block);
		}
		return $this;
	}

	public function add_header($header, $value) {
		if ('content-type' === strtolower($header)) {
			$this->content_type = $value;
		} else {
			$this->headers[$header] = $value;
		}
		return $this;
	}

	public function attach_view(\jolt\view $view) {
		$this->view = $view;
		return $this;
	}

	public function execute($argv) {
		if (!is_array($argv)) {
			$argv = array($argv);
		}
		$argc = count($argv);

		if (empty($this->action)) {
			throw new \jolt\exception('Controller action can not be empty.');
		}

		try {
			$action = new \ReflectionMethod($this, $this->action);
		} catch (\ReflectionException $e) {
			throw new \jolt\exception('Controller action method '.$this->action.' not actual member of controller class.');
		}

		$this->parse_accept_type();
		
		$param_count = $action->getNumberOfRequiredParameters();
		if ($param_count != $argc && $param_count > $argc) {
			$argv = array_pad($argv, $param_count, null);
		}

		ob_start();
			$init_executed_successfully = true;
			if (method_exists($this, 'init')) {
				$init_executed_successfully = $this->init();
			}

			if ($init_executed_successfully && $action->isPublic()) {
				if ($action->isStatic()) {
					$action->invokeArgs(null, $argv);
				} else {
					$action->invokeArgs($this, $argv);
				}
			}

			if (method_exists($this, 'shutdown')) {
				$this->shutdown();
			}
		$rendered_controller = ob_get_clean();

		if (!empty($rendered_controller)) {
			$this->rendered_controller = $rendered_controller;
		} else {
			$this->rendered_controller = $this->rendered_view;
		}

		return $this->rendered_controller;
	}

	public function register($variable, $value) {
		$this->__set($variable, $value);
		return $this;
	}

	public function render($view_name=null) {
		if (empty($view_name)) {
			$view_name = $this->action;
		}

		$this->rendered_view = $this->render_view($view_name);
		return $this->rendered_view;
	}

	public function render_to_block($view_name, $block_name) {
		$rendered_view = $this->render_view($view_name);
		$this->add_block($block_name, $rendered_view);

		return $this;
	}

	public function render_view($view_name) {
		$view = $this->check_view();

		$rendered_view = $view->render($view_name)
			->get_rendered_view();
		return $rendered_view;
	}

	public function url($path, $parameters=array(), $secure=false) {
		return $this->get_view()->url($path, $parameters, $secure);
	}
	
	public function get($key, $default=null, $expected=array()) {
		return $this->get_superglobal_value($key, $_GET, $default, $expected);
	}
	
	public function post($key, $default=null, $expected=array()) {
		return $this->get_superglobal_value($key, $_POST, $default, $expected);
	}

	public function set_action($action) {
		$this->action = trim($action);
		return $this;
	}

	public function set_content_type($content_type) {
		$this->content_type = trim($content_type);
		return $this;
	}

	public function set_response_code($response_code) {
		$this->response_code = intval($response_code);
		return $this;
	}
	
	public function set_route(\jolt\route $route) {
		$this->route = $route;
		return $this;
	}

	public function get_action() {
		return $this->action;
	}

	public function get_block_list() {
		if ($this->has_view()) {
			return $this->view->get_block_list();
		}
		return array();
	}

	public function get_block($block_name) {
		if ($this->has_view()) {
			return $this->view->get_block($block_name);
		}
		return null;
	}

	public function get_content_type() {
		return $this->content_type;
	}

	public function get_headers() {
		return $this->headers;
	}

	public function get_header($header) {
		if (isset($this->headers[$header])) {
			return $this->headers[$header];
		}
		return null;
	}

	public function get_rendered_controller() {
		return $this->rendered_controller;
	}

	public function get_rendered_view() {
		return $this->rendered_view;
	}

	public function get_response_code() {
		return $this->response_code;
	}
	
	public function get_route() {
		return $this->route;
	}

	public function get_get_params($key=null) {
		if (!empty($key)) {
			if (array_key_exists($key, $_GET)) {
				return $_GET[$key];
			} else {
				return array();
			}
		}
		return $_GET;
	}

	public function get_post_params($key=null) {
		if (!empty($key)) {
			if (array_key_exists($key, $_POST)) {
				return $_POST[$key];
			} else {
				return array();
			}
		}
		return $_POST;
	}

	public function get_input_params($key=null) {
		// The input stream is stored locally because once it is accessed through php://input,
		// it can't be accessed again.
		if (0 === count($this->input_params)) {
			$this->input_params = array();
			$input_stream = file_get_contents('php://input');

			parse_str($input_stream, $this->input_params);
		}

		if (!empty($key)) {
			if (array_key_exists($key, $this->input_params)) {
				return $this->input_params[$key];
			}
		}

		return $this->input_params;
	}

	public function get_view() {
		return $this->view;
	}
	
	public function is_accept_type_html() {
		return ('text/html' === $this->http_accept_type);
	}
	
	public function is_accept_type_json() {
		return ('text/json' === $this->http_accept_type);
	}
	
	public function is_accept_type_xml() {
		$xml_accept_types = array('text/xml', 'application/xml', 'application/xhtml+xml');
		return (in_array($this->http_accept_type, $xml_accept_types));
	}
	
	
	
	private function get_superglobal_value($key, $superglobal, $default, $expected) {
		$return = $default;
		
		if (is_array($superglobal) && array_key_exists($key, $superglobal)) {
			$return = $superglobal[$key];
			
			if (is_int($default)) {
				$return = (int)$return;
			} elseif (is_float($default)) {
				$return = (float)$return;
			} elseif (is_array($default)) {
				$return = (array)$return;
				
				if (is_array($expected) && count($expected) > 0) {
					$return = array_merge($expected, $return);
				}
			}
		}
		
		return $return;
	}
	
	private function parse_accept_type() {
		// Determine the content type from the headers
		$http_accept = filter_input(INPUT_SERVER, 'HTTP_ACCEPT');
		$http_accept_bits = explode(',', $http_accept);
		
		if (count($http_accept_bits) > 0) {
			// First element is the one we're interersted in
			$this->http_accept_type = trim(strtolower($http_accept_bits[0]));
		}
		
		return $this;
	}

	private function check_view() {
		if (!$this->has_view()) {
			throw new \jolt\exception('View not properly attached to Controller.');
		}
		return $this->view;
	}

	private function has_view() {
		return ($this->view instanceof \jolt\view);
	}

}