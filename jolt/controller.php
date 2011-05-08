<?php

declare(encoding='UTF-8');
namespace jolt;

class controller {

	private $action = NULL;
	private $content_type = 'text/html';
	private $rendered_controller = NULL;
	private $rendered_view = NULL;
	private $response_code = 200;
	private $view = NULL;

	private $headers = array();
	private $blocks = array();

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
		return NULL;
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

		$param_count = $action->getNumberOfRequiredParameters();
		if ($param_count != $argc && $param_count > $argc) {
			$argv = array_pad($argv, $param_count, NULL);
		}

		ob_start();
			$init_executed_successfully = true;
			if (method_exists($this, 'init')) {
				$init_executed_successfully = $this->init();
			}

			if ($init_executed_successfully && $action->isPublic()) {
				if ($action->isStatic()) {
					$action->invokeArgs(NULL, $argv);
				} else {
					$action->invokeArgs($this, $argv);
				}
			}
		$rendered_controller = ob_get_clean();

		if (!empty($rendered_controller)) {
			$this->rendered_controller = $rendered_controller;
		} else {
			$this->rendered_controller = $this->rendered_view;
		}

		if (method_exists($this, 'shutdown')) {
			$this->shutdown();
		}

		return $this->rendered_controller;
	}

	public function register($variable, $value) {
		$this->__set($variable, $value);
		return $this;
	}

	public function render($view_name=NULL) {
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
		return NULL;
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
		return NULL;
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

	public function get_post_params($key=NULL) {
		if (!empty($key)) {
			if (array_key_exists($key, $_POST)) {
				return $_POST[$key];
			} else {
				return array();
			}
		}
		return $_POST;
	}

	public function get_input_params($key=NULL) {
		$input_vars = array();
		$input_stream = file_get_contents('php://input');

		if (!empty($input_stream)) {
			parse_str($input_stream, $input_vars);
			if (!empty($key)) {
				if (array_key_exists($key, $input_vars)) {
					return $input_vars[$key];
				} else {
					return array();
				}
			}
		}
		return $input_vars;
	}

	public function get_view() {
		return $this->view;
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