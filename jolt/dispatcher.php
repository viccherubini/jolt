<?php

declare(encoding='UTF-8');
namespace jolt;

use \jolt\controller\locator as locator;

require_once('jolt/controller/locator.php');

class dispatcher {

	private $controller = NULL;
	private $controller_file = NULL;
	private $locator = NULL;
	private $rendered_controller = NULL;
	private $route = NULL;
	private $view = NULL;

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

	public function attach_view(\jolt\view $view) {
		$this->view = clone $view;
		return $this;
	}

	public function execute() {
		$locator = $this->check_locator();
		$route = $this->check_route();
		$view = $this->check_view();

		$controller = $this->locator->load($this->controller_file, $route->get_controller());
		$controller->attach_view($view)
			->set_action($route->get_action())
			->execute($route->get_argv());

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

	private function check_view() {
		if (is_null($this->view)) {
			throw new \jolt\exception('View not properly attached to Dispatcher.');
		}
		return $this->view;
	}
}