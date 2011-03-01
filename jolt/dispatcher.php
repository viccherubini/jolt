<?php

declare(encoding='UTF-8');
namespace jolt;
use \jolt\controller\locator as locator;

require_once('jolt/controller/locator.php');
require_once('jolt/redirect_exception.php');

class dispatcher {

	private $controller = NULL;
	private $controller_file = NULL;
	private $locator = NULL;
	private $rendered_controller = NULL;
	private $route = NULL;
	private $settings = NULL;
	private $view = NULL;

	const EXT = '.php';

	public function __construct() {

	}

	public function __destruct() {

	}

	public function attach_locator(\jolt\controller\locator $locator) {
		$this->locator = clone $locator;
		return $this;
	}

	public function attach_route(\jolt\route $route) {
		$this->route = clone $route;
		return $this;
	}

	public function attach_settings(\jolt\vo $settings) {
		$this->settings = clone $settings;
		return $this;
	}

	public function attach_view(\jolt\view $view) {
		$this->view = clone $view;
		return $this;
	}

	public function execute() {
		$locator = $this->check_locator();
		$route = $this->check_route();
		$settings = $this->check_settings();
		$view = $this->check_view();

		$controller = $route->get_controller();

		// If it's a fully namespaced controller, explode everything off
		// and get the last element, that's the name of the file.
		$controller_bits = explode('\\', $controller);
		$controller = end($controller_bits);

		$controller_path = $settings->controller_path;
		$controller_path_length = strlen($controller_path);
		if ($controller_path_length > 0 && $controller_path[$controller_path_length-1] != DIRECTORY_SEPARATOR) {
			$controller_path .= DIRECTORY_SEPARATOR;
		}

		$controller_path .= $controller.self::EXT;
		$controller = $this->locator->find($controller_path, $route->get_controller());

		try {
			$controller->attach_view($view)
				->set_action($route->get_action())
				->execute($route->get_argv());
		} catch (\jolt\redirect_exception $e) {
			// Set the right redirection information
			$controller->add_header('location', $e->get_location());
		}

		$this->controller = $controller;
		return true;
	}

	public function set_controller_file($controller_file) {
		$this->controller_file = $controller_file;
		return $this;
	}

	public function get_controller_file() {
		return $this->controller_file;
	}

	public function get_controller() {
		return $this->controller;
	}

	private function check_locator() {
		if (is_null($this->locator)) {
			throw new \jolt\exception('Locator not properly attached to Dispatcher.');
		}
		return $this->locator;
	}

	private function check_route() {
		if (is_null($this->route)) {
			throw new \jolt\exception('Route not properly attached to Dispatcher.');
		}
		return $this->route;
	}

	private function check_settings() {
		if (is_null($this->settings)) {
			throw new \jolt\exception('Settings not properly attached to Dispatcher.');
		}
		return $this->settings;
	}

	private function check_view() {
		if (is_null($this->view)) {
			throw new \jolt\exception('View not properly attached to Dispatcher.');
		}
		return $this->view;
	}

}